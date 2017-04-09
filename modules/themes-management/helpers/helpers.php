<?php

if (!function_exists('webed_themes_path')) {
    /**
     * @param string $path
     * @return string
     */
    function webed_themes_path($path = '')
    {
        return base_path('themes') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('themes_management')) {
    /**
     * @return \App\Module\ThemesManagement\Support\ThemesManagement
     */
    function themes_management()
    {
        return \App\Module\ThemesManagement\Facades\ThemesManagementFacade::getFacadeRoot();
    }
}

if (!function_exists('get_all_theme_information')) {
    /**
     * @return array
     */
    function get_all_theme_information()
    {
        $modulesArr = [];

        $canAccessDB = true;
        if (app()->runningInConsole()) {
            if (!check_db_connection() || !\Schema::hasTable('themes')) {
                $canAccessDB = false;
            }
        }

        /**
         * @var \App\Module\ThemesManagement\Repositories\ThemeRepository $themeRepo
         */
        $themeRepo = app(\App\Module\ThemesManagement\Repositories\Contracts\ThemeRepositoryContract::class);

        if ($canAccessDB) {
            $themes = $themeRepo->get();
        }

        $modules = get_folders_in_path(webed_themes_path());

        foreach ($modules as $row) {
            $file = $row . '/module.json';
            $data = json_decode(get_file_data($file), true);
            if ($data === null || !is_array($data)) {
                continue;
            }

            if ($canAccessDB) {
                $theme = $themes->where('alias', '=', array_get($data, 'alias'))->first();

                if (!$theme) {
                    $result = $themeRepo
                        ->editWithValidate(0, [
                            'alias' => array_get($data, 'alias'),
                            'enabled' => false,
                            'installed' => false,
                        ], true, true);
                    /**
                     * Everything ok
                     */
                    if (!$result['error']) {
                        $theme = $result['data'];
                    }
                }

                if ($theme) {
                    $data['enabled'] = !!$theme->enabled;
                    $data['installed'] = !!$theme->installed;
                    $data['id'] = $theme->id;
                    $data['installed_version'] = $theme->installed_version;
                }
            }

            $modulesArr[array_get($data, 'namespace')] = array_merge($data, [
                'file' => $file,
                'type' => 'themes',
                'require' => array_get($data, 'require') && is_array(array_get($data, 'require')) ? array_get($data, 'require') : []
            ]);
        }

        return $modulesArr;
    }
}

if (!function_exists('get_theme_information')) {
    /**
     * @param $alias
     * @return mixed
     */
    function get_theme_information($alias)
    {
        return themes_management()->getAllThemesInformation()
            ->where('alias', '=', $alias)
            ->first();
    }
}

if (!function_exists('theme_exists')) {
    /**
     * @param $alias
     * @return mixed
     */
    function theme_exists($alias)
    {
        return !!themes_management()->getAllThemesInformation()
            ->where('alias', '=', $alias)
            ->first();
    }
}

if (!function_exists('save_theme_information')) {
    /**
     * @param $alias
     * @param array $data
     * @return bool
     */
    function save_theme_information($alias, array $data)
    {
        $module = is_array($alias) ? $alias : get_theme_information($alias);
        if (!$module) {
            return false;
        }

        /**
         * @var \App\Module\ThemesManagement\Repositories\ThemeRepository $themeRepo
         */
        $themeRepo = app(\App\Module\ThemesManagement\Repositories\Contracts\ThemeRepositoryContract::class);

        $result = $themeRepo
            ->editWithValidate(array_get($module, 'id'), array_merge($data, [
                'alias' => array_get($module, 'alias'),
            ]), true, true);

        return !array_get($result, 'error');
    }
}

if (!function_exists('is_theme_enabled')) {
    /**
     * @param $alias
     * @return bool
     */
    function is_theme_enabled($alias)
    {
        $theme = get_theme_information($alias);
        if (!$theme || !array_get($theme, 'enabled')) {
            return false;
        }
        return true;
    }
}

if (!function_exists('is_theme_installed')) {
    /**
     * @param $alias
     * @return bool
     */
    function is_theme_installed($alias)
    {
        $theme = get_theme_information($alias);
        if (!$theme || !array_get($theme, 'installed')) {
            return false;
        }
        return true;
    }
}

if (!function_exists('get_current_theme')) {
    /**
     * Get current activated theme
     * @return mixed
     */
    function get_current_theme()
    {
        $currentTheme = themes_management()->getAllThemesInformation()
            ->where('enabled', '=', true)
            ->first();

        return $currentTheme;
    }
}
