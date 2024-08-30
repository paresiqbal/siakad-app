<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    // Route::get('/profile', [UserController::class, 'profile']);
    // Route::get('/dashboard', [DashboardController::class, 'index']);
});


Route::post("/users", [UserController::class, "register"]);
Route::post("/users/login", [UserController::class, "login"]);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
