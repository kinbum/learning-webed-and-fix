<?php
use App\Module\ThemesManagement\Support\UpdateThemesSupport;
use App\Module\ThemesManagement\Facades\UpdateThemesFacade;

if (!function_exists('register_theme_update_batches')) {
    /**
     * @param array $batches
     * @return UpdateThemesSupport
     */
    function register_theme_update_batches(array $batches)
    {
        return UpdateThemesFacade::registerUpdateBatches($batches);
    }
}

if (!function_exists('load_theme_update_batches')) {
    /**
     * @return UpdateThemesSupport
     */
    function load_theme_update_batches()
    {
        return UpdateThemesFacade::loadBatches();
    }
}
