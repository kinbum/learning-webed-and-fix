<?php namespace App\Module\ModulesManagement\Events;

class ModuleEnabled
{

    /**
     * @var array|string
     */
    public $module;

    /**
     * @param $module
     */
    public function __construct($module)
    {
        $this->module = $module;
    }
}
