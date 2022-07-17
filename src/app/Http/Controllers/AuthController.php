<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    protected $userService;
    
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request)
    {
        $user = $request->only(['name', 'email', 'password']);

        return $this->userService->register($user);
    }

    public function login(LoginRequest $request)
    {
        $email = $request->only('email');
        $password = $request->password;

        return $this->userService->login($email, $password);
    }

    public function logout(Request $request)
    {
        return $this->userService->logout($request);
    }

    public function verifyEmail(Request $request)
    {
        return $this->userService->verifyEmail(['id' => $request->route('id')]);
    }

    public function me(Request $request)
    {
        return $this->userService->me($request);
    }
}
