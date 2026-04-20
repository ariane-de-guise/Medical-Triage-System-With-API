<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', [AuthController::class, 'sendLoginRequest'])->name('send-login-request');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'sendRegisterRequest'])->name('send-register-request');
Route::get('/register', [AuthController::class, 'register'])->name('register');

Route::middleware(['api.auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/patient', [PatientController::class, 'index'])->name('patient');
    Route::post('/enqueue', [PatientController::class, 'ajoutFile'])->name('enqueue');
    Route::delete('/dequeue', [PatientController::class, 'retireFile'])->name('dequeue');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
