<?php namespace App\Module\Users\Http\Requests;

use App\Module\ACL\Repositories\Contracts\RoleRepositoryContract;
use App\Module\ACL\Repositories\RoleRepository;
use App\Module\Base\Http\Requests\Request;
use App\Module\Users\Models\User;
use App\Module\Users\Repositories\Contracts\UserRepositoryContract;
use App\Module\Users\Repositories\UserRepository;

class CreateUserRequest extends Request
{
    protected $rules = [
        'username' => 'required|between:3,100|string|alpha_dash',
        'email' => 'required|between:5,255|email',
        'password' => 'string|required',
        'status' => 'string|required|in:activated,disabled,deleted',
        'display_name' => 'string|between:1,150|nullable',
        'first_name' => 'string|between:1,100|required',
        'last_name' => 'string|between:1,100|nullable',
        'avatar' => 'string|between:1,150|nullable',
        'phone' => 'string|max:20|nullable',
        'mobile_phone' => 'string|max:20|nullable',
        'sex' => 'string|required|in:male,female,other',
        'birthday' => 'date_multi_format:Y-m-d H:i:s,Y-m-d|nullable',
        'description' => 'string|max:1000|nullable',
    ];
}
