<?php namespace App\Module\Acl\Http\Requests;

use App\Module\Base\Http\Requests\Request;

class UpdateRoleRequest extends Request
{
    public $rules = [
        'name' => 'required|max:255|string',
    ];
}
