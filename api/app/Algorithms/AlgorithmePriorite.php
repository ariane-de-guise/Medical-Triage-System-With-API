<?php

namespace App\Algorithms;

class AlgorithmePriorite
{
    public static function calculer(array $symptomes) : array {
        $afflictions = AfflictionMatcher::match($symptomes, Afflictions::afflictions);

        $priorities = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        $toRemove = [];
        $i = 0;

        foreach ($afflictions as $affliction) {
            if ($affliction['percentage'] > 0) {
                $priorities[$affliction['priorite']] += 1;
            }
            else {
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
