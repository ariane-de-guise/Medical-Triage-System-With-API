<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'date_naissance' => ['required', 'date'],
            'rue' => ['nullable', 'string', 'max:255'],
            'ville' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'code_postal' => ['nullable', 'string', 'max:20'],
            'type' => ['nullable', 'string', 'max:50'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        // 1️⃣ Photo
        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        // 2️⃣ Email
        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $user->forceFill([
                'email' => $input['email'],
                'email_verified_at' => null,
            ])->save();
            $user->sendEmailVerificationNotification();
        } else {
            $user->forceFill([
                'email' => $input['email'],
            ])->save();
        }

        // 3️⃣ Compte
        $user->compte()->update([
            'nom' => $input['nom'],
            'prenom' => $input['prenom'],
            'date_naissance' => $input['date_naissance'],
        ]);

        // 4️⃣ Adresse
        $adresse = $user->compte->adresses()->first(); // récupère la première adresse
        if ($adresse) {
            $adresse->update([
                'rue' => $input['rue'] ?? null,
                'ville' => $input['ville'] ?? null,
                'province' => $input['province'] ?? null,
                'code_postal' => $input['code_postal'] ?? null,
                'type' => $input['type'] ?? 'maison',
            ]);
        }

        // 5️⃣ Téléphone
        // 5️⃣ Téléphone
        $telephone = $user->compte->telephones()->first();
        if ($telephone) {
            $telephone->update([
                'numero' => $input['telephone'] ?? null,
            ]);
        }
    }
}
