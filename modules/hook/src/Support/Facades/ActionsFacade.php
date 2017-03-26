<?php namespace App\Module\Hook\Support\Facades;

use Illuminate\Support\Facades\Facade;
use App\Module\Hook\Support\Actions;

class ActionsFacade extends Facade {
    protected static function getFacadeAccessor () {
        return Actions::class;
    }
}