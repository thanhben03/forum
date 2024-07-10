<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;

class RegisterRequest extends BaseRequest
{
    protected function methodPost()
    {
        return [
            'fullname' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string',
            'username' => 'required|string|unique:users,username',
        ];
    }
}
