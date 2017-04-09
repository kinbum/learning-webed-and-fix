<?php namespace App\Module\ThemesManagement\Facades;

use Illuminate\Support\Facades\Facade;
use App\Module\ThemesManagement\Support\ThemeOptionsSupport;

class ThemeOptionsSupportFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ThemeOptionsSupport::class;
    }
}
