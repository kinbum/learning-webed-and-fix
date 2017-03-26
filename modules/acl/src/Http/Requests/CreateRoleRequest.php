<?php namespace App\Module\Acl\Http\Requests;

use App\Module\Base\Http\Requests\Request;

class CreateRoleRequest extends Request
{
    public $rules = [
        'name' => 'required|max:255|string',
        'slug' => 'required|max:255|alpha_dash',
    ];
}
