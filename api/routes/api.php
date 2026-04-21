<?php

use App\Http\Controllers\Admin\PersonnelController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedecinController;
use App\Http\Controllers\MetaController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;

Route::middleware('auth:sanctum')->group(function () {
    // Profil
    Route::prefix('profiles')->controller(ProfileController::class)->group(function () {
        Route::get('/{id}', 'show')->name('profile.show');
    });

    Route::middleware('ability:patient')->group(function () {
        // Patient
        Route::prefix('patients')->controller(PatientController::class)->group(function () {
            Route::get('/{id}', 'index')->name('patient.index');
            Route::post('/{id}', 'store')->name('patient.store');
            Route::delete('/{id}', 'destroy')->name('patient.destroy');
        });
    });

    Route::middleware('ability:medecin')->group(function () {
        // Médecin
        Route::prefix('medecins')->controller(MedecinController::class)->group(function () {
            Route::get('/{id}', 'index')->name('medecin.index');
            Route::get('/{id}/consultation', 'show')->name('medecin.show');
            Route::post('/{id}', 'store')->name('medecin.store');
            Route::delete('/{id}', 'destroy')->name('medecin.destroy');
            Route::patch('/{id}', 'update')->name('medecin.update');
        });
    });

    Route::middleware('ability:admin')->group(function () {
        // Admin
        Route::prefix('admins')->group(function () {
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

            /**
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

            // Compte
            Route::prefix('comptes')->controller(CompteController::class)->group(function () {
                Route::get('/', 'index')->name('comptes.index');
                Route::get('/create', 'create')->name('comptes.create');
                Route::post('/store', 'store')->name('comptes.store');
                Route::get('/{id}', 'show')->name('comptes.show');
                Route::get('/{id}/edit', 'edit')->name('comptes.edit');
                Route::put('/update', 'update')->name('comptes.update');
                Route::delete('/{id}', 'destroy')->name('comptes.destroy');

                // Visite
                Route::prefix('comptes/{id}/visites')->controller(VisiteController::class)->group(function () {
                    Route::get('/', 'index')->name('visites.index');
                    Route::get('/create', 'create')->name('visites.create');
                    Route::post('/store', 'store')->name('visites.store');
                    Route::get('/{id}', 'show')->name('visites.show');
                    Route::get('/{id}/edit', 'edit')->name('visites.edit');
                    Route::put('/update', 'update')->name('visites.update');
                    Route::delete('/{id}', 'destroy')->name('visites.destroy');
                });

                // Diagnostique
                Route::prefix('comptes/{id}/diagnostiques')->controller(DiagnostiqueController::class)->group(function () {
                    Route::get('/', 'index')->name('diagnostiques.index');
                    Route::get('/create', 'create')->name('diagnostiques.create');
                    Route::post('/store', 'store')->name('diagnostiques.store');
                    Route::get('/{id}', 'show')->name('diagnostiques.show');
                    Route::get('/{id}/edit', 'edit')->name('diagnostiques.edit');
                    Route::put('/update', 'update')->name('diagnostiques.update');
                    Route::delete('/{id}', 'destroy')->name('diagnostiques.destroy');
                });

                // Adresse
                Route::prefix('comptes/{id}/adresses')->controller(AdresseController::class)->group(function () {
                    Route::get('/', 'index')->name('adresses.index');
                    Route::get('/create', 'create')->name('adresses.create');
                    Route::post('/store', 'store')->name('adresses.store');
                    Route::get('/{id}', 'show')->name('adresses.show');
                    Route::get('/{id}/edit', 'edit')->name('adresses.edit');
                    Route::put('/update', 'update')->name('adresses.update');
                    Route::delete('/{id}', 'destroy')->name('adresses.destroy');
                });

                // Téléphone
                Route::prefix('comptes/{id}/telephones')->controller(TelephoneController::class)->group(function () {
                    Route::get('/', 'index')->name('telephones.index');
                    Route::get('/create', 'create')->name('telephones.create');
                    Route::post('/store', 'store')->name('telephones.store');
                    Route::get('/{id}', 'show')->name('telephones.show');
                    Route::get('/{id}/edit', 'edit')->name('telephones.edit');
                    Route::put('/update', 'update')->name('telephones.update');
                    Route::delete('/{id}', 'destroy')->name('telephones.destroy');
                });
            });

            // User
            Route::prefix('users')->controller(ProfileController::class)->group(function () {
                Route::get('/', 'index')->name('users.index');
                Route::get('/create', 'create')->name('users.create');
                Route::post('/store', 'store')->name('users.store');
                Route::get('/{id}', 'show')->name('users.show');
                Route::get('/{id}/edit', 'edit')->name('users.edit');
                Route::put('/update', 'update')->name('users.update');
                Route::delete('/{id}', 'destroy')->name('users.destroy');
            });
            */
        });
    });

    //Auth
    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
});

// Meta
Route::controller(MetaController::class)->group(function () {
    Route::get('/provinces', 'provinces')->name('meta.provinces');
    Route::get('/types', 'types')->name('meta.types');
});

// Auth
Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login')->name('auth.login');
    Route::post('/register', 'register')->name('auth.register');
});
