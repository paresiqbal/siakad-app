<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// user route
Route::post("/users", [UserController::class, "register"]);
Route::post("/users/login", [UserController::class, "login"]);
Route::post('/logout', [UserController::class, 'logout']);

// news route
Route::post('/news', [NewsController::class, 'create']);
Route::put('/news/{id}', [NewsController::class, 'update']);
Route::get('/news/{id}', [NewsController::class, 'show']);
Route::get('/news', [NewsController::class, 'index']);
