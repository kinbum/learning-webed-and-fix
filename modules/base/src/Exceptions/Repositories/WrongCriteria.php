<?php namespace App\Module\Base\Exceptions\Repositories;

use InvalidArgumentException;

class WrongCriteria extends InvalidArgumentException
{
    public function __construct($message = "")
    {
        return parent::__construct($message);
    }
}
