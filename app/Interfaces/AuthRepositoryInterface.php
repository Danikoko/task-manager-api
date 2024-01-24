<?php

namespace App\Interfaces;

interface AuthRepositoryInterface
{
    public function register(array $registerDetails);
    public function login(array $loginDetails);
    public function logout();
}
