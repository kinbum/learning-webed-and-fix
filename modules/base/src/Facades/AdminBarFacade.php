<?php namespace App\Module\Base\Facades;

use Illuminate\Support\Facades\Facade;
use App\Module\Base\Support\AdminBar;

class AdminBarFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return AdminBar::class;
    }
}
