<?php namespace App\Module\ThemesManagement\Facades;

use Illuminate\Support\Facades\Facade;
use App\Module\ThemesManagement\Support\ThemesManagement;

class ThemesManagementFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ThemesManagement::class;
    }
}
