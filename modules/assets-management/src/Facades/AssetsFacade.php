<?php namespace App\Module\AssetManagement\Facades;

use Illuminate\Support\Facades\Facade;
use App\Module\AssetManagement\Assets;

class AssetsFacade extends Facade {
    protected static function getFacadeAccessor () {
        return Assets::class;
    } 
}