<?php

namespace App\Algorithms;

/**
 * Calcule la priorité de triage à partir des symptômes soumis.
 */
class AlgorithmePriorite
{
    /**
     * Détermine la priorité finale et les meilleures afflictions correspondantes.
     *
     * @param list<string> $symptomes Symptômes déclarés pour le patient.
     * @return array{priorite:int|string, afflictions:list<array{nom:string, priorite?:int, percentage?:float}>}
     */
    public static function calculer(array $symptomes): array
    {
        $afflictions = AfflictionMatcher::match($symptomes, Afflictions::afflictions);

        $priorities = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        $toRemove = [];
        $i = 0;

        foreach ($afflictions as $affliction) {
            if ($affliction['percentage'] > 0) {
                $priorities[$affliction['priorite']] += 1;
            } else {
                $toRemove[] = $i;
            }
            $i++;
        }

        foreach ($toRemove as $i) {
            unset($afflictions[$i]);
        }

        if (max($priorities) === 0) {
            $priorite = 5;
        } else {
            $priorite = array_search(max($priorities), $priorities);
        }

        if (empty($afflictions)) {
            $afflictions[] = ['nom' => 'Aucun match avec les afflictions connues.'];
        }

        return [
            'priorite' => $priorite,
            'afflictions' => $afflictions,
        ];
    }
}
