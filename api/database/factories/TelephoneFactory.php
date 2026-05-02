<?php

namespace Database\Factories;

use App\Models\Telephone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Génère des numéros de téléphone factices.
 */
class TelephoneFactory extends Factory
{
    protected $model = Telephone::class;

    /**
     * Retourne un jeu de données valide pour un téléphone.
     *
     * @return array{numero:string}
     */
    public function definition(): array
    {
        return [
            'numero' => fake()->numerify('##########'),
        ];
    }
}
