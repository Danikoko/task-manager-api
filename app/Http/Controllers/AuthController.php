<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\AuthRepositoryInterface;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();
        return $this->authRepository->register($validatedData);
    }

    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();
        return $this->authRepository->login($validatedData);
    }

    public function logout()
    {
        return $this->authRepository->logout();
    }
}
