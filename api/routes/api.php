<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MetaController;
use App\Http\Controllers\UserController;

Route::get('/provinces', [MetaController::class, 'provinces']);
Route::get('/types', [MetaController::class, 'types']);
Route::get('/user/{id}', [UserController::class, 'show']);
Route::get('/patient/{id}', [UserController::class, 'patient']);
Route::delete('/patient/{id}/remove', [UserController::class, 'retireFile']);
Route::post('/patient/{id}/add', [UserController::class, 'ajoutFile']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
