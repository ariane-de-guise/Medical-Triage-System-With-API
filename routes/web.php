<?php

use App\Algorithms\TriageQueue;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return view('dashboard', compact('user'));
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', function () {
            return view('admin');
        })->name('admin');
    });

    /*
    |--------------------------------------------------------------------------
    | MEDECIN
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:medecin'])->group(function () {
        Route::get('/medecin', function () {
            return view('medecin');
        })->name('medecin');
    });

    /*
    |--------------------------------------------------------------------------
    | INFIRMIER
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:infirmier'])->group(function () {
        Route::get('/infirmier', function () {
            return view('infirmier');
        })->name('infirmier');
    });

    /*
    |--------------------------------------------------------------------------
    | PATIENT
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:patient'])->group(function () {
        Route::get('/patient', function () {
            return view('patient');
        })->name('patient');

        Route::post('/patient/enqueue', function () {
            $patient = auth()->user()->compte;
            abort_if(!$patient, 403);

            TriageQueue::enqueue(auth()->user(), 0);

            return redirect()
                ->route('patient')
                ->with('success', 'Vous avez été ajouté à la file d\'attente');
        })->name('patient.enqueue');

        Route::post('/patient/dequeue', function () {
            $patient = auth()->user()->compte;
            abort_if(!$patient, 403);

            TriageQueue::dequeue(auth()->user());

            return redirect()
                ->route('patient')
                ->with('success', 'Vous avez été retiré de la file d\'attente');
        })->name('patient.dequeue');
    });
});
