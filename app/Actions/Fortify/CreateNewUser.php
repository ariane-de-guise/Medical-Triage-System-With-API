<?php

namespace App\Actions\Fortify;

use App\Models\Adresse;
use App\Models\Telephone;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        // 1️⃣ Create user
        $user = User::create([
            'email' => $input['email'],
            'password' => bcrypt($input['password']),
        ]);

        // 2️⃣ Create compte
        $compte = $user->compte()->create([
            'nom' => $input['nom'] ?? '',
            'prenom' => $input['prenom'] ?? '',
            'date_naissance' => $input['date_naissance'] ?? null,
        ]);

        $adresse = Adresse::create([
            'numero' => $input['numero'] ?? '',
            'rue' => $input['rue'] ?? '',
            'ville' => $input['ville'] ?? '',
            'province' => $input['province'] ?? '',
            'code_postal' => $input['code_postal'] ?? '',
            'type' => 'home',
        ]);

        $compte->adresses()->attach($adresse->id, [
            'type' => 'home'
        ]);

        // 4️⃣ Create telephone
        $telephone = Telephone::create([
            'numero' => $input['telephone'] ?? '',
        ]);

        $compte->telephones()->attach($telephone->id);
        return $user;
    }
}
