<?php

namespace App\Interfaces;

interface ProfileRepositoryInterface
{
    public function updateProfile(array $taskDetails);
    public function getProfile();
}
