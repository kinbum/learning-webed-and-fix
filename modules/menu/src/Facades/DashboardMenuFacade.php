<?php namespace App\Module\Menu\Facades;

use Illuminate\Support\Facades\Facade;
use App\Module\Menu\Support\DashboardMenu;

class DashboardMenuFacade extends Facade {
    public static function getFacadeAccessor () {
        return DashboardMenu::class;
    }
}