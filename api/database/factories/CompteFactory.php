<?php

namespace Database\Factories;

use App\Models\Compte;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Génère des comptes factices et leur utilisateur associé.
 */
class CompteFactory extends Factory
{
    protected $model = Compte::class;

    /**
     * Retourne un jeu de données valide pour un compte.
     *
     * @return array{nom:string, prenom:string, date_naissance:string, user_id:User|Factory<User>}
     */
    public function definition(): array
    {
        return [
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'date_naissance' => fake()->date(),
            'user_id' => User::factory(),
        ];
    }
}
