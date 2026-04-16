<?php

namespace Database\Factories;

use App\Models\Telephone;
use Illuminate\Database\Eloquent\Factories\Factory;

class TelephoneFactory extends Factory
{
    protected $model = Telephone::class;

    public function definition(): array
    {
        return [
            'numero' => fake()->numerify('##########'),
        ];
    }
}
