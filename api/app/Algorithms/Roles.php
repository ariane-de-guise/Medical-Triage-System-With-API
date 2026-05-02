<?php

namespace App\Algorithms;

/**
 * Enum des rôles applicatifs utilisés par la logique de triage.
 *
 * Ces valeurs servent à filtrer l'accès, choisir la file d'attente,
 * et répartir les patients entre le personnel disponible.
 */
enum Roles: string
{
    case Admin = 'admin';
    case Medecin = 'medecin';
    case Infirmier = 'infirmier';
    case Patient = 'patient';
}
