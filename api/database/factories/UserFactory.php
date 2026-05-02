<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * Génère des utilisateurs factices pour l'authentification API.
 *
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Retourne un jeu de données valide pour un utilisateur.
     *
     * @return array{email:string, password:string}
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
        ];
    }
}
