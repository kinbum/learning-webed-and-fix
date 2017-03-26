<?php namespace App\Module\Hook\Support\Facades;

use Illuminate\Support\Facades\Facade;
use App\Module\Hook\Support\Filters;

class FiltersFacade extends Facade {
    protected static function getFacadeAccessor () {
        return Filters::class;
    }
}