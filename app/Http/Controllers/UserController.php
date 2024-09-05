<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Register a new user
    public function register(UserRegisterRequest $request)
    {
        $data = $request->validated();

        if (User::where('username', $data['username'])->exists()) {
            throw new HttpResponseException(response([
                'errors' => [
                    'username' => ['Username already exists'],
                ],
            ], 400));
        }

        // Create a new user
        $user = User::create([
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
        ]);

        // Generate token using Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return user data and token
        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
        ], 201);
    }



    // Login a user
    public function login(UserLoginRequest $request)
    {
        $data = $request->validated();

        // Find user by username
        $user = User::where('username', $data['username'])->first();

        // Check if user exists and password matches
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new HttpResponseException(response([
                'errors' => [
                    'username' => ['Invalid username or password'],
                ],
            ], 400));
        }

        // Generate Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return user data and token
        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
        ], 200);
    }


    // Logout a user
    public function logout(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();

        // Revoke the user's current token
        if ($user) {
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'message' => 'User logged out successfully',
        ], 200);
    }
}
