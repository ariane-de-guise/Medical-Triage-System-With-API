<?php

namespace Database\Factories;

use App\Models\Diagnostique;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiagnostiqueFactory extends Factory
{
    protected $model = Diagnostique::class;

    public function definition(): array
    {
        return [
            'commentaire' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'priorite' => fake()->numberBetween(1, 5),
        ];
    }

    public function forPatient(int $compteId): self
    {
        return $this->state([
            'patient_id' => $compteId,
        ]);
    }

    public function afterVisite($date_heure): self
    {
        return $this->state([
            'date_heure' => \Carbon\Carbon::parse($date_heure)
                ->copy()
                ->addHours(8),
        ]);
    }
}
