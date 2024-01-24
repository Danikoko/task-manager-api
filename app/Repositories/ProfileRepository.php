<?php

namespace App\Repositories;

use App\Interfaces\ProfileRepositoryInterface;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class ProfileRepository implements ProfileRepositoryInterface
{
    public function updateProfile(array $profileDetails): JsonResponse
    {
        $profile = Profile::where('user_id', auth('sanctum')->id())->first();
        $profileId = $profile->id;

        $oldImagePath = $profile->image;
        $oldImageName = str_replace('storage', 'public', $oldImagePath);

        $filePath = NULL;
        $fileName = NULL;
        if (isset($profileDetails['file'])) {
            $filePath = Storage::putFile('public/profile_images', $profileDetails['file']);
            $fileName = str_replace('public', 'storage', $filePath);
        }

        // Clean the passed in parameters up
        $profileDetails['image'] = $fileName;

        unset($profileDetails['file']);

        // Update the profile
        $profileUpdated = Profile::whereId($profileId)->update($profileDetails);
        if ($profileUpdated) {
            // Delete the old profile image if it exists
            if ($profile->image) {
                Storage::delete($oldImageName);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully.'
            ], 201);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'The profile couldn\'t be updated.'
            ], 401);
        }
    }

    public function getProfile(): JsonResponse
    {
        $profile = Profile::where('user_id', auth('sanctum')->id())->first();

        return response()->json([
            'status' => 'success',
            'data' => $profile
        ], 201);
    }
}
