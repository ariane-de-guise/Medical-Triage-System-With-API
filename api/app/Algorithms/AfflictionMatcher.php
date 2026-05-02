<?php

namespace App\Algorithms;

/**
 * Associe une liste de symptômes patients aux afflictions connues.
 */
class AfflictionMatcher
{
    /**
     * Retourne les 5 afflictions (maladies/blessures) qui correspondent le mieux.
     *
     * @param list<string> $inputSymptomes Symptômes saisis pour le patient.
     * @param list<array{nom:string, priorite:int, symptomes:list<string>}> $afflictions Catalogue complet des afflictions.
     * @return list<array{nom:string, priorite:int, percentage:float}> Top 5 des afflictions triées par pourcentage de correspondance décroissant.
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
