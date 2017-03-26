<?php namespace App\Module\Base\Facades;

use Illuminate\Support\Facades\Facade;
use App\Module\Base\Services\FlashMessages;

class FlashMessagesFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return FlashMessages::class;
    }
}
