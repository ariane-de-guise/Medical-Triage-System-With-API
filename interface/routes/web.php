<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdresseController;
use App\Http\Controllers\Admin\CompteController;
use App\Http\Controllers\Admin\PersonnelController;
use App\Http\Controllers\Admin\TriageController;
use App\Http\Controllers\Admin\VisiteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InfirmierController;
use App\Http\Controllers\MedecinController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::post('/login', [AuthController::class, 'sendLoginRequest'])->name('send-login-request');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'sendRegisterRequest'])->name('send-register-request');
Route::get('/register', [AuthController::class, 'register'])->name('register');

Route::middleware(['api.auth'])->group(function () {
    // General routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

    // Patient routes
    Route::prefix('patient')->controller(PatientController::class)->group(function () {
        Route::get('/', 'index')->name('patient');
        Route::post('/enqueue', 'store')->name('enqueue');
        Route::delete('/dequeue', 'destroy')->name('dequeue');
    });

    // Médecin routes
    Route::prefix('medecin')->controller(MedecinController::class)->group(function () {
        Route::get('/', 'index')->name('medecin');
        Route::get('/consultation', 'show')->name('medecin.show');
        Route::post('/login', 'store')->name('medecin.login');
        Route::delete('/logout', 'destroy')->name('medecin.logout');
        Route::patch('/consultation/update', 'update')->name('medecin.update');
    });

    // Infirmier routes
    Route::prefix('infirmier')->controller(InfirmierController::class)->group(function () {
        Route::get('/', 'index')->name('infirmier');
        Route::get('/consultation', 'show')->name('infirmier.show');
        Route::post('/login', 'store')->name('infirmier.login');
        Route::delete('/logout', 'destroy')->name('infirmier.logout');
        Route::patch('/consultation/update', 'update')->name('infirmier.update');
    });

    // Admin routes
    Route::prefix('admin')->controller(AdminController::class)->group(function () {
        Route::get('/', 'index')->name('admin');

        // Personnel
        Route::prefix('personnels')->controller(PersonnelController::class)->group(function () {
            Route::get('/', 'index')->name('personnels.index');
            Route::get('/create', 'create')->name('personnels.create');
            Route::post('/store', 'store')->name('personnels.store');
            Route::get('/{id}', 'show')->name('personnels.show');
            Route::get('/{id}/edit', 'edit')->name('personnels.edit');
            Route::put('/update', 'update')->name('personnels.update');
            Route::delete('/{id}', 'destroy')->name('personnels.destroy');
        });

        // Triage
        Route::prefix('triages')->controller(TriageController::class)->group(function () {
            Route::get('/', 'index')->name('triages.index');
            Route::get('/create', 'create')->name('triages.create');
            Route::post('/store', 'store')->name('triages.store');
            Route::get('/{id}', 'show')->name('triages.show');
            Route::get('/{id}/edit', 'edit')->name('triages.edit');
            Route::put('/update', 'update')->name('triages.update');
            Route::delete('/{id}', 'destroy')->name('triages.destroy');
        });

        // Triage
        Route::prefix('comptes')->controller(CompteController::class)->group(function () {
            Route::get('/', 'index')->name('comptes.index');
            Route::get('/create', 'create')->name('comptes.create');
            Route::post('/store', 'store')->name('comptes.store');
            Route::get('/{id}', 'show')->name('comptes.show');
            Route::get('/{id}/edit', 'edit')->name('comptes.edit');
            Route::put('/update', 'update')->name('comptes.update');
            Route::delete('/{id}', 'destroy')->name('comptes.destroy');

            // Visite
            Route::prefix('{compte_id}/visites')->controller(VisiteController::class)->group(function () {
                Route::get('/', 'index')->name('visites.index');
                Route::get('/create', 'create')->name('visites.create');
                Route::post('/store', 'store')->name('visites.store');
                Route::get('/{id}', 'show')->name('visites.show');
                Route::get('/{id}/edit', 'edit')->name('visites.edit');
                Route::put('/update', 'update')->name('visites.update');
                Route::delete('/{id}', 'destroy')->name('visites.destroy');
            });

            // Adresse
            Route::prefix('{compte_id}/adresses')->controller(AdresseController::class)->group(function () {
                Route::get('/', 'index')->name('adresses.index');
                Route::get('/create', 'create')->name('adresses.create');
                Route::post('/store', 'store')->name('adresses.store');
                Route::get('/{id}', 'show')->name('adresses.show');
                Route::get('/{id}/edit', 'edit')->name('adresses.edit');
                Route::put('/update', 'update')->name('adresses.update');
                Route::delete('/{id}', 'destroy')->name('adresses.destroy');
            });
        });
    });

    // Auth
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
