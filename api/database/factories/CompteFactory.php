<?php

namespace Database\Factories;

use App\Models\Compte;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompteFactory extends Factory
{
    protected $model = Compte::class;

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
