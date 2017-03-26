<?php namespace App\Module\Acl\Facades;

use Illuminate\Support\Facades\Facade;
use App\Module\Acl\Support\CheckUserACL;

class CheckUserACLFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return CheckUserACL::class;
    }
}
