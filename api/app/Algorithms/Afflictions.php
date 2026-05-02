<?php

namespace App\Algorithms;

/**
 * Référentiel des afflictions utilisées par l'algorithme de triage.
 */
class Afflictions
{
    /**
     * Catalogue des afflictions connues.
     *
     * @var list<array{nom:string, priorite:int, symptomes:list<string>}>
     */
    public const array afflictions = [
        [
            'nom' => 'Arrêt cardiaque',
            'priorite' => 1,
            'symptomes' => [
                Symptomes::PERTE_CONSCIENCE->value,
                Symptomes::COMA->value,
            ]
        ],

        [
            'nom' => 'Arrêt respiratoire',
            'priorite' => 1,
            'symptomes' => [
                Symptomes::ARRET_RESPIRATOIRE->value,
                Symptomes::RESPIRATION_DIFFICILE->value,
            ]
        ],

        [
            'nom' => 'Hémorragie avec choc immédiat',
            'priorite' => 1,
            'symptomes' => [
                Symptomes::SAIGNEMENT_SEVERE->value,
                Symptomes::SIGNES_VITAUX_INSTABLES->value,
            ]
        ],

        [
            'nom' => 'AVC / ACV',
            'priorite' => 1,
            'symptomes' => [
                Symptomes::PARALYSIE->value,
                Symptomes::ENGOURDISSEMENT->value,
                Symptomes::TROUBLES_PAROLE->value,
                Symptomes::TROUBLES_VISUELS->value,
                Symptomes::MAUX_TETE_SEVERE->value,
            ]
        ],

        [
            'nom' => 'Crise cardiaque',
            'priorite' => 1,
            'symptomes' => [
                Symptomes::NAUSEE->value,
                Symptomes::FATIGUE->value,
                Symptomes::ESSOUFFLEMENT->value,
                Symptomes::DOULEUR_THORACIQUE->value,
            ]
        ],

        [
            'nom' => 'Trauma grave',
            'priorite' => 1,
            'symptomes' => [
                Symptomes::TRAUMATISME->value,
                Symptomes::DOULEUR_SEVERE->value,
            ]
        ],

        [
            'nom' => 'Sepsis',
            'priorite' => 1,
            'symptomes' => [
                Symptomes::FIEVRE->value,
                Symptomes::FREQUENCE_CARDIAQUE_ELEVEE->value,
                Symptomes::MALAISE_GENERAL->value,
            ]
        ],

        [
            'nom' => 'Fracture importante',
            'priorite' => 2,
            'symptomes' => [
                Symptomes::DOULEUR_SEVERE->value,
                Symptomes::GONFLEMENT_MODERE->value,
                Symptomes::HEMATOME->value,
                Symptomes::CRAQUEMENT_OSSEUX->value,
            ]
        ],

        [
            'nom' => 'Choc anaphylactique',
            'priorite' => 2,
            'symptomes' => [
                Symptomes::GONFLEMENT_VISAGE_GORGE->value,
                Symptomes::RESPIRATION_DIFFICILE->value,
                Symptomes::CHUTE_TENSION->value,
            ]
        ],

        [
            'nom' => 'Hémorragie sans choc immédiat',
            'priorite' => 2,
            'symptomes' => [
                Symptomes::SAIGNEMENT_CONTENU->value,
                Symptomes::SIGNES_VITAUX_STABLES->value,
            ]
        ],

        [
            'nom' => 'Crise d’asthme sévère',
            'priorite' => 2,
            'symptomes' => [
                Symptomes::SIFFLEMENT->value,
                Symptomes::DIFFICULTE_PARLER->value,
                Symptomes::OPPRESSION_THORACIQUE->value,
            ]
        ],

        [
            'nom' => 'Traumatisme crânien',
            'priorite' => 2,
            'symptomes' => [
                Symptomes::CONFUSION->value,
                Symptomes::SOMNOLENCE->value,
                Symptomes::VOMISSEMENTS->value,
            ]
        ],

        [
            'nom' => 'Hypoglycémie sévère',
            'priorite' => 2,
            'symptomes' => [
                Symptomes::TREMBLEMENTS->value,
                Symptomes::CONFUSION->value,
                Symptomes::SUEURS->value,
            ]
        ],

        [
            'nom' => 'Embolie pulmonaire',
            'priorite' => 2,
            'symptomes' => [
                Symptomes::DOULEUR_THORACIQUE->value,
                Symptomes::ESSOUFFLEMENT_SOUDAIN->value,
                Symptomes::TACHYCARDIE->value,
            ]
        ],

        [
            'nom' => 'Vomissements de sang',
            'priorite' => 2,
            'symptomes' => [
                Symptomes::VOMISSEMENTS_SANG->value,
            ]
        ],

        [
            'nom' => 'Hypertension extrême',
            'priorite' => 2,
            'symptomes' => [
                Symptomes::HYPERTENSION_EXTREME->value,
                Symptomes::MAUX_TETE_SEVERE->value,
            ]
        ],

        [
            'nom' => 'Respiration difficile',
            'priorite' => 3,
            'symptomes' => [
                Symptomes::RESPIRATION_DIFFICILE->value,
            ]
        ],

        [
            'nom' => 'Fracture légère',
            'priorite' => 3,
            'symptomes' => [
                Symptomes::DOULEUR_LOCALISEE->value,
                Symptomes::ECCHYMOSE->value,
                Symptomes::GONFLEMENT_MODERE->value,
            ]
        ],

        [
            'nom' => 'Entorse importante',
            'priorite' => 3,
            'symptomes' => [
                Symptomes::DOULEUR_SEVERE->value,
                Symptomes::GONFLEMENT_MODERE->value,
                Symptomes::MOBILITE_REDUITE->value,
            ]
        ],

        [
            'nom' => 'Appendicite',
            'priorite' => 3,
            'symptomes' => [
                Symptomes::DOULEUR_ABDOMINALE->value,
                Symptomes::FIEVRE->value,
                Symptomes::NAUSEE->value,
            ]
        ],

        [
            'nom' => 'Pneumonie',
            'priorite' => 3,
            'symptomes' => [
                Symptomes::FIEVRE->value,
                Symptomes::TOUX->value,
                Symptomes::ESSOUFFLEMENT->value,
            ]
        ],

        [
            'nom' => 'Infection urinaire compliquée',
            'priorite' => 3,
            'symptomes' => [
                Symptomes::DOULEUR_URINAIRE->value,
                Symptomes::FIEVRE->value,
                Symptomes::BRULURES_URINAIRES->value,
            ]
        ],

        [
            'nom' => 'Asthme',
            'priorite' => 3,
            'symptomes' => [
                Symptomes::RESPIRATION_DIFFICILE->value,
                Symptomes::SIFFLEMENT->value,
            ]
        ],

        [
            'nom' => 'Entorse',
            'priorite' => 4,
            'symptomes' => [
                Symptomes::DOULEUR_LEGERE->value,
                Symptomes::GONFLEMENT_LEGER->value,
            ]
        ],

        [
            'nom' => 'Infection mineure',
            'priorite' => 4,
            'symptomes' => [
                Symptomes::INFECTION_MINEURE->value,
            ]
        ],

        [
            'nom' => 'Coupure légère',
            'priorite' => 4,
            'symptomes' => [
                Symptomes::COUPURE_LEGERE->value,
                Symptomes::SAIGNEMENT_LEGER->value,
            ]
        ],

        [
            'nom' => 'Problème cutané',
            'priorite' => 4,
            'symptomes' => [
                Symptomes::PROBLEME_CUTANE->value,
            ]
        ],

        [
            'nom' => 'Rhume',
            'priorite' => 5,
            'symptomes' => [
                Symptomes::ETERNUMENTS->value,
                Symptomes::ECOULEMENT_NASAL->value,
                Symptomes::IRRITATION_GORGE->value,
                Symptomes::FATIGUE->value,
            ]
        ],

        [
            'nom' => 'Écorchure',
            'priorite' => 5,
            'symptomes' => [
                Symptomes::ECORCHURE->value,
            ]
        ],

        [
            'nom' => 'Douleur chronique stable',
            'priorite' => 5,
            'symptomes' => [
                Symptomes::DOULEUR_CHRONIQUE->value,
            ]
        ],

        [
            'nom' => 'Vomissements légers',
            'priorite' => 5,
            'symptomes' => [
                Symptomes::VOMISSEMENTS_LEGERS->value,
            ]
        ],

        [
            'nom' => 'Diarrhée',
            'priorite' => 5,
            'symptomes' => [
                Symptomes::DIARRHEE->value,
            ]
        ],

        [
            'nom' => 'Écharde',
            'priorite' => 5,
            'symptomes' => [
                Symptomes::ECHARDE->value,
            ]
        ],
    ];
}

