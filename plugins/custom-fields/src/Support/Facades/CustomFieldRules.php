<?php namespace App\Plugins\CustomFields\Support\Facades;

use Illuminate\Support\Facades\Facade;

class CustomFieldRules extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \App\Plugins\CustomFields\Support\CustomFieldRules::class;
    }
}
