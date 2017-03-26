<?php namespace App\Module\Users\Http\Requests;

use App\Module\Base\Http\Requests\Request;

class UpdateUserPasswordRequest extends Request
{
    public $rules = [
        'password' => 'required|max:60|confirmed|min:5|string'
    ];
}
