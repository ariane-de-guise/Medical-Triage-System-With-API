<?php

use App\Http\Controllers\Admin\AdresseController;
use App\Http\Controllers\Admin\CompteController;
use App\Http\Controllers\Admin\PersonnelController;
use App\Http\Controllers\Admin\TelephoneController;
use App\Http\Controllers\Admin\TriageController;
use App\Http\Controllers\Admin\VisiteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InfirmierController;
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

    Route::middleware('ability:infirmier')->group(function () {
        // Médecin
        Route::prefix('infirmiers')->controller(InfirmierController::class)->group(function () {
            Route::get('/{id}', 'index')->name('infirmier.index');
            Route::get('/{id}/consultation', 'show')->name('infirmier.show');
            Route::post('/{id}', 'store')->name('infirmier.store');
            Route::delete('/{id}', 'destroy')->name('infirmier.destroy');
            Route::patch('/{id}', 'update')->name('infirmier.update');
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

                // Téléphone
                Route::prefix('{compte_id}/telephones')->controller(TelephoneController::class)->group(function () {
                    Route::get('/', 'index')->name('telephones.index');
                    Route::post('/store', 'store')->name('telephones.store');
                    Route::get('/{id}', 'show')->name('telephones.show');
                    Route::get('/{id}/edit', 'edit')->name('telephones.edit');
                    Route::put('/update', 'update')->name('telephones.update');
                    Route::delete('/{id}', 'destroy')->name('telephones.destroy');
                });
            });
        });
    });

    //Auth
    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
});

// Meta
Route::controller(MetaController::class)->group(function () {
    Route::get('/provinces', 'provinces')->name('meta.provinces');
    Route::get('/types', 'types')->name('meta.types');
    Route::get('/ressources', 'resources')->name('meta.resources');
});

// Auth
Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login')->name('auth.login');
    Route::post('/register', 'register')->name('auth.register');
});
