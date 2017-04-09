<?php namespace App\Plugins\CustomFields\Http\Requests;

use App\Module\Base\Http\Requests\Request;

class UpdateFieldGroupRequest extends Request
{
    public $rules = [

    ];

    public function authorize()
    {
        //return $this->user()->hasPermission('edit-page');
        return true;
    }
}
