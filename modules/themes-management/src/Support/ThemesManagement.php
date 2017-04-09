<?php namespace App\Module\ThemesManagement\Support;

use App\Module\Base\Http\Controllers\BaseFrontController;

class ThemesManagement
{
    /**
     * @var array|null
     */
    protected $currentTheme;

    /**
     * @var array
     */
    protected $themes;

    public function __construct()
    {
        $this->themes = collect(get_all_theme_information());
        $this->currentTheme = $this->getCurrentTheme();
    }

    /**
     * Enable a theme
     * @param $alias
     * @return $this
     */
    public function enableTheme($alias)
    {
        $theme = $this->themes->where('alias', '=', $alias)->first();
        if (!$theme) {
            throw new \RuntimeException('Theme not found: ' . $alias);
        }

        /**
         * Save theme information
         */
        save_theme_information($theme, [
            'enabled' => true
        ]);

        $this->modifyModuleAutoload($alias, false);

        /**
         * Disable other themes
         */
        foreach ($this->themes->where('alias', '<>', $alias) as $other) {
            $this->disableTheme(array_get($other, 'alias'));
        }

        return $this;
    }

    /**
     * @param $alias
     * @return $this
     */
    public function disableTheme($alias)
    {
        $theme = $this->themes->where('alias', '=', $alias)->first();
        if (!$theme) {
            throw new \RuntimeException('Theme not found: ' . $alias);
        }

        /**
         * Save theme information
         */
        save_theme_information($theme, [
            'enabled' => false
        ]);

        $this->modifyModuleAutoload($alias, true);

        return $this;
    }

    /**
     * Modify the composer autoload information
     * @param $alias
     * @param bool $isDisabled
     */
    public function modifyModuleAutoload($alias, $isDisabled = false)
    {
        $theme = $this->themes->where('alias', '=', $alias)->first();
        if (!$theme) {
            return $this;
        }

        $moduleAutoloadType = array_get($theme, 'autoload', 'psr-4');
        $relativePath = str_replace(base_path() . '/', '', str_replace('module.json', '', array_get($theme, 'file', ''))) . 'src';

        $moduleNamespace = array_get($theme, 'namespace');

        if (!$moduleNamespace) {
            return $this;
        }

        if (substr($moduleNamespace, -1) !== '\\') {
            $moduleNamespace .= '\\';
        }

        /**
         * Composer information
         */
        $composerContent = json_decode(\File::get(base_path('composer.json')), true);
        $autoload = array_get($composerContent, 'autoload', []);

        if (!array_get($autoload, $moduleAutoloadType)) {
            $autoload[$moduleAutoloadType] = [];
        }

        if ($isDisabled === true) {
            if (isset($autoload[$moduleAutoloadType][$moduleNamespace])) {
                unset($autoload[$moduleAutoloadType][$moduleNamespace]);
            }
        } else {
            if ($moduleAutoloadType === 'classmap') {
                $autoload[$moduleAutoloadType][] = $relativePath;
            } else {
                $autoload[$moduleAutoloadType][$moduleNamespace] = $relativePath;
            }
        }
        $composerContent['autoload'] = $autoload;

        /**
         * Save file
         */
        \File::put(base_path('composer.json'), json_encode_prettify($composerContent));

        return $this;
    }

    public function refreshComposerAutoload()
    {
        return modules_management()->refreshComposerAutoload();
    }

    /**
     * @return array|null
     */
    public function getCurrentTheme()
    {
        return $this->themes->where('enabled', '=', true)->first();
    }

    /**
     * @var string $controllerName
     * @return BaseFrontController|null
     */
    public function getThemeController($controllerName)
    {
        if (!$this->currentTheme || !trim($controllerName)) {
            return null;
        }

        $controller = str_replace('\\\\', '\\', array_get($this->currentTheme, 'namespace') . '\Http\Controllers\\' . $controllerName . 'Controller');

        try {
            return app($controller);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    /**
     * @return array|\Illuminate\Support\Collection
     */
    public function getAllThemesInformation()
    {
        return $this->themes;
    }
}
