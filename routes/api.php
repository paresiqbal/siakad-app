<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// user route
Route::post("/users", [UserController::class, "register"]);
Route::post("/users/login", [UserController::class, "login"]);
Route::post('/logout', [UserController::class, 'logout']);

// news route
Route::post('/create-news', [NewsController::class, 'create']);
Route::get('/get-news/{id}', [NewsController::class, 'show']);
Route::get('/list-news', [NewsController::class, 'index']);
Route::put('/update-news/{id}', [NewsController::class, 'update']);
Route::delete('/delete-news/{id}', [NewsController::class, 'destroy']);
