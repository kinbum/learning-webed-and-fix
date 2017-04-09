<?php namespace App\Module\ThemesManagement\Repositories;

use App\Module\Base\Repositories\Eloquent\EloquentBaseRepository;
use App\Module\ThemesManagement\Repositories\Contracts\ThemeOptionRepositoryContract;

class ThemeOptionRepository extends EloquentBaseRepository implements ThemeOptionRepositoryContract
{
    protected $rules = [

    ];

    protected $editableFields = [
        '*',
    ];

    /**
     * @param $id
     * @return array
     */
    public function getOptionsByThemeId($id)
    {
        $result = [];
        $query = $this->model
            ->join('themes', 'theme_options.theme_id', '=', 'themes.id')
            ->where('themes.id', '=', $id)
            ->select('theme_options.key', 'theme_options.value')
            ->get();
        foreach ($query as $item) {
            $result[$item->key] = $item->value;
        }
        return $result;
    }

    /**
     * @param $alias
     * @return array
     */
    public function getOptionsByThemeAlias($alias)
    {
        $result = [];
        $query = $this->model
            ->join('themes', 'theme_options.theme_id', '=', 'themes.id')
            ->where('themes.alias', '=', $alias)
            ->select('theme_options.key', 'theme_options.value')
            ->get();
        foreach ($query as $item) {
            $result[$item->key] = $item->value;
        }
        return $result;
    }

    /**
     * @param array $options
     * @return array|bool
     */
    public function updateOptions($options = [])
    {
        foreach ($options as $key => $row) {
            $result = $this->updateOption($key, $row);
            if ($result['error']) {
                return $result;
            }
        }
        return response_with_messages('Options updated', false, \Constants::SUCCESS_NO_CONTENT_CODE);
    }

    /**
     * @param $key
     * @param $value
     * @return array
     */
    public function updateOption($key, $value)
    {
        $currentTheme = cms_theme_options()->getCurrentTheme();
        if (!$currentTheme) {
            return response_with_messages('No theme activated!!!', true, \Constants::ERROR_CODE);
        }

        $allowCreateNew = true;
        $justUpdateSomeFields = false;

        /**
         * Parse everything to string
         */
        $value = (string)$value;

        $option = $this
            ->where([
                'key' => $key,
                'theme_id' => array_get($currentTheme, 'id'),
            ])
            ->select(['id', 'key', 'value'])
            ->first();

        if ($option) {
            $allowCreateNew = false;
            $justUpdateSomeFields = true;
        }

        $result = $this->editWithValidate($option, [
            'key' => $key,
            'value' => $value,
            'theme_id' => array_get($currentTheme, 'id'),
        ], $allowCreateNew, $justUpdateSomeFields);

        if ($result['error']) {
            return response_with_messages($result['messages'], true, \Constants::ERROR_CODE, [
                'key' => $key,
                'value' => $value
            ]);
        }

        return response_with_messages('Options updated', false, \Constants::SUCCESS_NO_CONTENT_CODE);
    }
}
