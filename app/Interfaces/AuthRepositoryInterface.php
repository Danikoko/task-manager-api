<?php

namespace App\Interfaces;

interface AuthRepositoryInterface
{
    public function register($registerDetails);
    public function login($loginDetails);
    public function logout();
}
