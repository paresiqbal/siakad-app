<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    $user = $request->user();

    return response()->json([
        'id' => $user->id,
        'username' => $user->username,
    ]);
})->middleware('auth:sanctum');


// user route
Route::post("/register", [UserController::class, "register"]);
Route::post("/login", [UserController::class, "login"]);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

// news route
Route::post('/create-news', [NewsController::class, 'store']);
Route::get('/get-news/{id}', [NewsController::class, 'show']);
Route::get('/list-news', [NewsController::class, 'index']);
Route::put('/update-news/{id}', [NewsController::class, 'update']);
Route::delete('/delete-news/{id}', [NewsController::class, 'destroy']);
