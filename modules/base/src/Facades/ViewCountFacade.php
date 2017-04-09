<?php namespace App\Module\Base\Facades;

use Illuminate\Support\Facades\Facade;
use App\Module\Base\Support\ViewCount;

class ViewCountFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ViewCount::class;
    }
}
