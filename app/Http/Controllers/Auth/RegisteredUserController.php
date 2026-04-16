<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Compte;
use App\Models\Adresse;
use App\Models\Telephone;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1️⃣ Validation
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'rue' => 'nullable|string|max:255',
            'numero' => 'nullable|integer',
            'ville' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:20',
            'type' => 'nullable|string|max:50',
            'telephone' => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($request, &$user) {
            // 2️⃣ Créer l'utilisateur
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // 3️⃣ Créer le compte lié
            $compte = Compte::create([
                'user_id' => $user->id,
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'date_naissance' => $request->date_naissance,
            ]);
            $user->assignRole('patient');

            // 4️⃣ Créer et lier l'adresse
            $adresse = Adresse::create([
                'rue' => $request->rue,
                'numero' => $request->numero,
                'ville' => $request->ville,
                'province' => $request->province,
                'code_postal' => $request->code_postal,
                'type' => $request->type,
            ]);
            $compte->adresses()->attach($adresse->id, ['type' => $request->type == 'travail' ? 'work' : 'home']);

            // 5️⃣ Créer et lier le téléphone
            if ($request->telephone) {
                $telephone = Telephone::create(['numero' => $request->telephone]);
                $compte->telephones()->attach($telephone->id);
            }
        });

        // 6️⃣ Event + login
        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
