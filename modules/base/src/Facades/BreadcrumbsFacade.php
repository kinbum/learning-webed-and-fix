<?php namespace App\Module\Base\Facades;

use Illuminate\Support\Facades\Facade;
use App\Module\Base\Support\Breadcrumbs;

class BreadcrumbsFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Breadcrumbs::class;
    }
}
