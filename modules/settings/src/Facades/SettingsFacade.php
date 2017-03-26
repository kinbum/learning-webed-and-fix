<?php namespace App\Module\Settings\Facades;

use Illuminate\Support\Facades\Facade;
use App\Module\Settings\Support\Settings;

class SettingsFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Settings::class;
    }
}
