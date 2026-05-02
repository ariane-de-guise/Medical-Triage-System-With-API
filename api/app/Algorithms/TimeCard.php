<?php

namespace App\Algorithms;

use App\Models\Personnel;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;


/**
 * Gère la connexion/déconnexion du personnel clinique.
 */
class TimeCard
{
    /**
     * Connecte un utilisateur comme membre du personnel (médecin ou infirmier).
     *
     * @param User $user Utilisateur courant.
     * @param Roles $role Rôle de personnel à activer.
     * @return void Les erreurs sont capturées et journalisées.
     */
    public static function login(User $user, Roles $role): void
    {
        $compte = $user->compte;

        try {
            DB::transaction(function () use ($role, $compte) {
                if (Personnel::where('compte_id', $compte->id)->exists()) {
                    throw new Exception("Ce compte est déjà connecté.");
                }

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
     * Déconnecte un utilisateur de son rôle de personnel clinique.
     *
     * @param User $user Utilisateur courant.
     * @param Roles $role Rôle de personnel à désactiver.
     * @return void Les erreurs sont capturées et journalisées.
     */
    public static function logout(User $user, Roles $role): void
    {
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

    /**
     * Vérifie si l'utilisateur est actuellement connecté avec le rôle demandé.
     *
     * @param User $user Utilisateur courant.
     * @param Roles $role Rôle à valider.
     * @return bool True si un enregistrement de personnel existe pour ce compte et ce rôle.
     */
    public static function loggedin(User $user, Roles $role): bool
    {
        return Personnel::where('compte_id', $user->compte->id)
            ->where('role', $role)
            ->exists();
    }
}
