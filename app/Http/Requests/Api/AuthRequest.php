<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends BaseRequest
{
    protected function methodPost()
    {
        return [
            'name' => 'nullable|string',
            'password' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
        ];
    }
}
