<?php

namespace App\Algorithms;

/**
 * Enum representant les differents symptomes de maladies et blessures (afflictions) des patients.
 */
enum Symptomes: string
{
    case AGITATION = 'agitation';
    case ARRET_CARDIAQUE = 'arrêt cardiaque';
    case ARRET_RESPIRATOIRE = 'arrêt respiratoire';
    case BRULURES_URINAIRES = 'brûlures urinaires';
    case CHUTE_TENSION = 'chute de tension';
    case COMA = 'coma';
    case CONFUSION = 'confusion';
    case COUPURE_LEGERE = 'coupure légère';
    case CRAQUEMENT_OSSEUX = 'craquement osseux';
    case DIARRHEE = 'diarrhée';
    case DIFFICULTE_PARLER = 'difficulté à parler';
    case DOULEUR_ABDOMINALE = 'douleur abdominale';
    case DOULEUR_CHRONIQUE = 'douleur chronique';
    case DOULEUR_LEGERE = 'douleur légère';
    case DOULEUR_MODERE = 'douleur modéré';
    case DOULEUR_SEVERE = 'douleur sévère';
    case DOULEUR_LOCALISEE = 'douleur localisée';
    case DOULEUR_LOMBAIRE = 'douleur lombaire';
    case DOULEUR_THORACIQUE = 'douleur thoracique';
    case DOULEUR_URINAIRE = 'douleur urinaire';
    case ECCHYMOSE = 'ecchymose';
    case ECOULEMENT_NASAL = 'écoulement nasal';
    case ECORCHURE = 'écorchure';
    case ECHARDE = 'écharde';
    case ENGOURDISSEMENT = 'engourdissement';
    case ESSOUFFLEMENT = 'essoufflement';
    case ESSOUFFLEMENT_SOUDAIN = 'essoufflement soudain';
    case ETERNUMENTS = 'éternuements';
    case FATIGUE = 'fatigue';
    case FIEVRE = 'fièvre';
    case FREQUENCE_CARDIAQUE_BASSE = 'fréquence cardiaque basse';
    case FREQUENCE_CARDIAQUE_ELEVEE = 'fréquence cardiaque élevée';
    case GONFLEMENT_LEGER = 'gonflement léger';
    case GONFLEMENT_MODERE = 'gonflement modéré';
    case GONFLEMENT_SEVERE = 'gonflement sévère';
    case GONFLEMENT_RAPIDE = 'gonflement rapide';
    case GONFLEMENT_VISAGE_GORGE = 'gonflement visage/gorge';
    case HEMATOME = 'hématome';
    case HYPERTENSION_EXTREME = 'tension extrêmement élevée';
    case INFECTION_MINEURE = 'infection mineure';
    case INFECTION_MODERE = 'infection modéré';
    case INFECTION_SEVERE = 'infection sévère';
    case INFECTION_POITRINE = 'infection poitrine';
    case IRRITATION_GORGE = 'irritation de la gorge';
    case IRRITATION_MINEURE = 'irritation mineure';
    case IRRITATION_MODERE = 'irritation modéré';
    case IRRITATION_SEVERE = 'irritation sévère';
    case MAUX_TETE_MINEURE = 'maux de tête mineure';
    case MAUX_TETE_SEVERE = 'maux de tête sévère';
    case MALAISE_GENERAL = 'malaise général';
    case MOBILITE_PARTIELLE = 'mobilité partielle';
    case MOBILITE_REDUITE = 'mobilité réduite';
    case MORSURE_MINEURE = 'morsure mineure';
    case MORSURE_SEVERE = 'morsure sévère';
    case NAUSEE = 'nausée';
    case OPPRESSION_THORACIQUE = 'oppression thoracique';
    case PARALYSIE = 'paralysie';
    case PERTE_CONSCIENCE = 'perte de conscience';
    case PROBLEME_CUTANE = 'problème cutané';
    case RESPIRATION_DIFFICILE = 'respiration difficile';
    case SAIGNEMENT_LEGER = 'saignement léger';
    case SAIGNEMENT_MODERE = 'saignement modéré';
    case SAIGNEMENT_ELEVEE = 'saignement élevée';
    case SAIGNEMENT_SEVERE = 'saignement sévère';
    case SAIGNEMENT_CONTENU = 'saignement contenu';
    case SIGNES_VITAUX_INSTABLES = 'signes vitaux instables';
    case SIGNES_VITAUX_STABLES = 'signes vitaux stables';
    case SIFFLEMENT = 'sifflement';
    case SOMNOLENCE = 'somnolence';
    case SUEURS = 'sueurs';
    case TACHYCARDIE = 'tachycardie';
    case TENSION_ELEVEE = 'tension élevée';
    case TOUX = 'toux';
    case TRAUMATISME = 'traumatisme';
    case TREMBLEMENTS = 'tremblements';
    case TROUBLES_PAROLE = 'troubles de la parole';
    case TROUBLES_VISUELS = 'troubles visuels';
    case VOMISSEMENTS_LEGERS = 'vomissements légers';
    case VOMISSEMENTS = 'vomissements modérés';
    case VOMISSEMENTS_ELEVES = 'vomissements élevés';
    case VOMISSEMENTS_SANG = 'vomissements de sang';
}
