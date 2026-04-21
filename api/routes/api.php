<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedecinController;
use App\Http\Controllers\MetaController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\UserController;

// Meta
Route::controller(MetaController::class)->group(function () {
    Route::get('/provinces', 'provinces')->name('meta.provinces');
    Route::get('/types', 'types')->name('meta.types');
});

// User
Route::prefix('user')->controller(UserController::class)->group(function () {
    Route::get('/{id}', 'show')->name('user.show');
});

// Patient
Route::prefix('patient')->controller(PatientController::class)->group(function () {
    Route::get('/{id}', 'index')->name('patient.index');
    Route::post('/{id}', 'store')->name('patient.store');
    Route::delete('/{id}', 'destroy')->name('patient.destroy');
});

// Médecin
Route::prefix('medecin')->controller(MedecinController::class)->group(function () {
    Route::get('/{id}', 'index')->name('medecin.index');
    Route::get('/{id}/consultation', 'show')->name('medecin.show');
    Route::post('/{id}', 'store')->name('medecin.store');
    Route::delete('/{id}', 'destroy')->name('medecin.destroy');
    Route::patch('/{id}', 'update')->name('medecin.update');
});

// Auth
Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login')->name('auth.login');
    Route::post('/register', 'register')->name('auth.register');
});
