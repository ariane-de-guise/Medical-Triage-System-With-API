<?php

namespace App\Algorithms;

use App\Models\Personnel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;


class TimeCard
{
    /**
     * Fonction pour être capable de se connecter en tant que médecin ou infirmier.
     *
     * @param User $user Utilisateur de la session.
     * @param Roles $role Rôle que la vue souhaite afficher (vérifications faites dans les routes pour les permissions).
     * @return void Exception si problème lors de la création du personnel ou du rééquilibrage des files d'attente.
     */
    public static function login(User $user, Roles $role): void
    {
        $compte = $user->compte;

        try {
            DB::transaction(function () use ($role, $compte) {
                Personnel::create([
                    'compte_id' => $compte->id,
                    'role' => $role,
                ]);

                TriageQueue::rebalance($role);
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Fonction pour être capable de se déconnecter en tant que médecin ou infirmier.
     *
     * @param User $user Utilisateur de la session.
     * @param Roles $role Rôle que la vue souhaite afficher (vérifications faites dans les routes pour les permissions).
     * @return void Exception si problème lors de la création du personnel ou du rééquilibrage des files d'attente.
     */
    public static function logout(User $user, Roles $role): void {
        $compte = $user->compte;

        try {
            DB::transaction(function () use ($role, $compte) {
                $personnel = $compte->personnel;
                $personnel->triages()->where('file_attente', $role)->update(['personnel_id' => null]);
                $personnel->delete();
                TriageQueue::rebalance($role);
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage());
        }
    }

    public static function loggedin(User $user): bool {
        $compte = $user->compte;
        $personnel = $compte->personnel;
        if ($personnel) {
            return true;
        }
        return false;
    }
}
