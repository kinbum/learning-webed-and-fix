<?php namespace App\Module\ThemesManagement\Facades;

use Illuminate\Support\Facades\Facade;
use App\Module\ThemesManagement\Support\UpdateThemesSupport;

class UpdateThemesFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return UpdateThemesSupport::class;
    }
}
