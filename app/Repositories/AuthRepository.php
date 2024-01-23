<?php

namespace App\Repositories;

use App\Interfaces\AuthRepositoryInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
    public function register($registerDetails): JsonResponse
    {
        $registerDetails['password'] = Hash::make($registerDetails['password']);
        $user = User::create($registerDetails);

        // Attempt login
        if (Auth::loginUsingId($user->id)) {
            $token = $user->createToken("API TOKEN")->plainTextToken;
            // Authentication was successful...

            return response()->json([
                'status' => 'success',
                'message' => 'Logged in successfully.',
                'user' => $user,
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Authentication failed.'
            ], 403);
        }
    }

    public function login($loginDetails): JsonResponse
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
