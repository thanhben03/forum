<?php

namespace App\Http\Controllers\Api;

use App\Api\Repositories\User\UserRepositoryInterface;
use App\Api\Services\User\UserServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private array $user;
    public function __construct(
        UserRepositoryInterface $repository,
        UserServiceInterface $service
    )
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);
        $credentials = $request->only('username', 'password');

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = auth('api')->user();
        $this->user = [
            'username' => $user->username,
            'email' => $user->email,
        ];
        return $this->respondWithToken($token);
    }

    public function register(RegisterRequest $request){

        $user = $this->service->store($request);
        $this->user = [
            'username' => $user->name,
            'email' => $user->email,
        ];
        $token = auth('api')->login($user);
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];

        if (!empty($this->user)) {
            $data['user'] = $this->user;
        }
        return response()->json($data);
    }

}
