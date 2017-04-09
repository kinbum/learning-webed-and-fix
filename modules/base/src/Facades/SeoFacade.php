<?php namespace App\Module\Base\Facades;

use Illuminate\Support\Facades\Facade;
use App\Module\Base\Support\SEO;

class SeoFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SEO::class;
    }
}
