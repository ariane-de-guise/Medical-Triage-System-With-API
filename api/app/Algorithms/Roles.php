<?php

namespace App\Algorithms;

/**
 * Enum représentant les rôles des utilisateurs dans le système de triage.
 * Chaque rôle correspond à un type de personnel ou à un patient.
 * Les rôles sont utilisés pour déterminer les files d'attente et les priorités de triage.
 */
enum Roles: string
{
    case Admin = 'admin';
    case Medecin = 'medecin';
    case Infirmier = 'infirmier';
    case Patient = 'patient';
}
