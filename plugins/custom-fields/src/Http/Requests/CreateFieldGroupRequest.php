<?php namespace App\Plugins\CustomFields\Http\Requests;

use App\Module\Base\Http\Requests\Request;

class CreateFieldGroupRequest extends Request
{
    public $rules = [
        'order' => 'integer|min:0',
        'rules' => 'json|required',
        'title' => 'string|required|max:255',
        'status' => 'required|in:activated,disabled',
    ];
}
