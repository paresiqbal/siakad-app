<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    // Register a new user
    public function register(UserRegisterRequest $request): UserResource
    {
        $data = $request->validated();

        if (User::where("username", $data["username"])->exists()) {
            throw new HttpResponseException(response([
                "errors" => [
                    "username" => ["Username already exists"],
                ],
            ], 400));
        }

        // Create a new user
        $user = new User($data);
        $user->password = Hash::make($data["password"]);
        $user->save();

        return new UserResource($user);
    }

    // Login a user
    public function login(UserLoginRequest $request): UserResource
    {
        $data = $request->validated();
        $user = User::where("username", $data["username"])->first();
        if (!$user && Hash::check($data["password"], $user->password) == false) {
            throw new HttpResponseException(response([
                "errors" => [
                    "username" => ["Invalid username or password"],
                ],
            ], 400));
        }

        // Generate a new token
        $user->token = Str::uuid()->toString();
        $user->save();

        return new UserResource($user);
    }

    // Logout a user
    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->token = null;
            $user->save();
        }

        return response()->json([
            "message" => "User logged out successfully",
        ], 200);
    }
}
