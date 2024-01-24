<?php

namespace App\Repositories;

use App\Interfaces\AuthRepositoryInterface;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
    public function register(array $registerDetails): JsonResponse
    {
        // Check if the email exists
        $existingEmail = User::where('email', $registerDetails['email'])->first();
        if ($existingEmail) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please use a different email.'
            ], 401);
        }

        $registerDetails['password'] = Hash::make($registerDetails['password']);
        $user = User::create($registerDetails);

        if ($user) {
            Profile::create([
                'user_id' => $user->id,
                'first_name' => $user->name
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'There was an issue creating this account.'
            ], 401);
        }

        // Attempt login
        if (Auth::loginUsingId($user->id)) {
            $token = $user->createToken("API TOKEN")->plainTextToken;
            // Authentication was successful...

            return response()->json([
                'status' => 'success',
                'message' => 'Logged in successfully.',
                'user' => $user,
                'profile' => $user->profile,
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Authentication failed.'
            ], 403);
        }
    }

    public function login(array $loginDetails): JsonResponse
    {
        $email = $loginDetails['email'];
        $password = $loginDetails['password'];

        $user = User::where('email', $email)->first();

        // Return if the user is not found
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Account not found.'
            ], 403);
        }

        // Attempt login
        if (
            Auth::attempt([
                'email' => $email,
                'password' => $password
            ])
        ) {
            $token = $user->createToken("API TOKEN")->plainTextToken;
            // Authentication was successful...

            return response()->json([
                'status' => 'success',
                'message' => 'Logged in successfully.',
                'user' => $user,
                'profile' => $user->profile,
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Authentication failed.'
            ], 403);
        }
    }

    public function logout()
    {
        $user = auth('sanctum')->user();
        if ($user->currentAccessToken()->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Access token revoked successfully.'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Logout failed.',
            ], 401);
        }
    }
}
