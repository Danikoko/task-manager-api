<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\ProfileRepositoryInterface;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;

class ProfileController extends Controller
{
    private ProfileRepositoryInterface $profileRepository;

    public function __construct(ProfileRepositoryInterface $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function updateProfile(ProfileRequest $request)
    {
        $validatedData = $request->validated();
        return $this->profileRepository->updateProfile($validatedData);
    }
}
