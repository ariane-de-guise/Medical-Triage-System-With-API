<?php

namespace App\Algorithms;

class AfflictionMatcher
{
    /**
     * Fonction qui retourne les 5 afflictions (maladies et blessures)
     * qui correspondes les plus aux symptomes du patient.
     *
     * @param array $inputSymptomes Liste des symptomes en input du patient
     * @param array $afflictions Liste complete des afflictions
     * @return array Top 5 afflictions avec montant de match en %
     */
    public static function match(array $inputSymptomes, array $afflictions): array
    {
        $results = [];

        foreach ($afflictions as $affliction) {

            $symptomes = $affliction['symptomes'];

            if (count($symptomes) === 0) {
                continue;
            }

            $matches = 0;

            foreach ($symptomes as $symptome) {
                if (in_array($symptome, $inputSymptomes, true)) {
                    $matches++;
                }
            }

            $percentage = ($matches / count($symptomes)) * 100;

            $results[] = [
                'nom' => $affliction['nom'],
                'priorite' => $affliction['priorite'],
                'percentage' => round($percentage, 2),
            ];
        }

        usort($results, function ($a, $b) {
            return $b['percentage'] <=> $a['percentage'];
        });

        return array_slice($results, 0, 5);
    }
}
