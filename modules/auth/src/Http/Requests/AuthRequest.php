<?php namespace App\Module\Auth\Http\Requests;

use App\Module\Base\Http\Requests\Request;

class AuthRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected $rules = [
        'username' => 'required',
        'password' => 'required',
    ];
}
