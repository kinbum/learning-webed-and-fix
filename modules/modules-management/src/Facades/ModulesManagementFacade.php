<?php namespace App\Module\ModulesManagement\Facades;

use Illuminate\Support\Facades\Facade;

class ModulesManagementFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \App\Module\ModulesManagement\Support\ModulesManagement::class;
    }
}
