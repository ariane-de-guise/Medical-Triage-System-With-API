<?php

namespace Database\Factories;

use App\Models\Diagnostique;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Génère des diagnostiques factices avec des états métier utiles.
 */
class DiagnostiqueFactory extends Factory
{
    protected $model = Diagnostique::class;

    /**
     * Retourne un jeu de données valide pour un diagnostique.
     *
     * @return array{commentaire:string, description:string, priorite:int}
     */
    public function definition(): array
    {
        return [
            'commentaire' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'priorite' => fake()->numberBetween(1, 5),
        ];
    }

    /**
     * Force l'association du diagnostique à un patient précis.
     *
     * @param int $compteId Identifiant du compte patient.
     * @return self
     */
    public function forPatient(int $compteId): self
    {
        return $this->state([
            'patient_id' => $compteId,
        ]);
    }

    /**
     * Positionne l'horodatage du diagnostique après la visite.
     *
     * @param mixed $date_heure Date/heure de référence de la visite.
     * @return self
     */
    public function afterVisite(mixed $date_heure): self
    {
        return $this->state([
            'date_heure' => Carbon::parse($date_heure)
                ->copy()
                ->addHours(8),
        ]);
    }
}
