<?php

if (!function_exists('cms_theme_options')) {
    /**
     * @return \WebEd\Base\ThemesManagement\Support\ThemeOptionsSupport
     */
    function cms_theme_options()
    {
        return \ThemeOptions::getFacadeRoot();
    }
}

if (!function_exists('get_theme_options')) {
    /**
     * @param null $key
     * @param null $default
     * @return array|string
     */
    function get_theme_options($key = null, $default = null)
    {
        return cms_theme_options()->getOption($key, $default);
    }
}
