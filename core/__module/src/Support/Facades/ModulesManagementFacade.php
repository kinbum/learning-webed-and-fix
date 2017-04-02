<?php namespace Core\Module\Support\Facades;

use Illuminate\Support\Facades\Facade;

class ModulesManagementFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Core\Module\Support\ModulesManagement::class;
    }
}
