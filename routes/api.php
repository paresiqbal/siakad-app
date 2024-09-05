<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\StaffDirController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// user route
Route::post("/users", [UserController::class, "register"]);
Route::post("/users/login", [UserController::class, "login"]);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

// news route
Route::post('/create-news', [NewsController::class, 'create']);
Route::get('/get-news/{id}', [NewsController::class, 'show']);
Route::get('/list-news', [NewsController::class, 'index']);
Route::put('/update-news/{id}', [NewsController::class, 'update']);
Route::delete('/delete-news/{id}', [NewsController::class, 'destroy']);

// agenda route
Route::post('/create-agenda', [AgendaController::class, 'create']);
Route::put('/update-agenda/{id}', [AgendaController::class, 'update']);
Route::delete('/delete-agenda/{id}', [AgendaController::class, 'destroy']);
Route::get('/list-agenda', [AgendaController::class, 'index']);

// staff directory route
Route::post('/create-staff', [StaffDirController::class, 'create']);
Route::put('/update-staff/{id}', [StaffDirController::class, 'update']);
Route::delete('/delete-staff/{id}', [StaffDirController::class, 'destroy']);
Route::get('/list-staff', [StaffDirController::class, 'index']);
