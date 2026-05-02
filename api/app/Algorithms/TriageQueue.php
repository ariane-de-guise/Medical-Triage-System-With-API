<?php

namespace App\Algorithms;

use App\Models\Compte;
use App\Models\Diagnostique;
use App\Models\Personnel;
use App\Models\Triage;
use App\Models\User;
use App\Models\Visite;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OutOfBoundsException;
use Throwable;

/**
 * Gère les opérations de file d'attente de triage (ajout, retrait, consultation, répartition).
 */
class TriageQueue
{
    /**
     * Retourne la taille de la file d'attente associée à un membre du personnel.
     *
     * @param User $user Utilisateur courant.
     * @return int Nombre de patients assignés (0 si aucun personnel lié au compte).
     */
    public static function queueCount(User $user): int
    {
        $compte = $user->compte;
        $personnel = $compte->personnel;
        if (!$personnel) {
            return 0;
        }
        return $personnel->triages()->count();
    }

    /**
     * Ajoute un patient dans une file d'attente (infirmier ou médecin).
     *
     * @param User $user Utilisateur courant.
     * @param int $priority Priorité de triage (0 = file infirmier, sinon file médecin).
     * @param string $commentaire Commentaire initial enregistré dans le diagnostique si assignation médecin.
     * @return void Les erreurs sont capturées et journalisées.
     */
    public static function enqueue(User $user, int $priority, string $commentaire): void
    {
        $compte = $user->compte;

        try {
            DB::transaction(function () use ($commentaire, $compte, $priority) {
                $last = Triage::lockForUpdate()->max('position') ?? 0;

                $data = [
                    'compte_id' => $compte->id,
                    'position' => $last + 1,
                    'priority' => $priority,
                ];

                if ($priority == 0) {
                    $infirmier = Personnel::withCount('triages')->where('role', Roles::Infirmier)->orderBy('triages_count')->first();
                    if (!$infirmier) {
                        throw new Exception("No practitioner available");
                    }
                    $data['personnel_id'] = $infirmier->id;
                    $data['file_attente'] = Roles::Infirmier;
                } else {
                    $medecin = Personnel::withCount('triages')->where('role', Roles::Medecin)->orderBy('triages_count')->first();
                    if (!$medecin) {
                        throw new Exception("No practitioner available");
                    }
                    $data['personnel_id'] = $medecin->id;
                    $data['file_attente'] = Roles::Medecin;

                    $diagnostique = new Diagnostique();
                    $diagnostique->commentaire = $commentaire;
                    $diagnostique->patient()->associate($compte);
                    $diagnostique->description = "À remplir";
                    $diagnostique->date_heure = now()->utc();
                    $diagnostique->priorite = $priority;
                    $diagnostique->save();

                    $visite = new Visite();
                    $visite->docteur()->associate($medecin->compte);
                    $visite->patient()->associate($compte);
                    $visite->diagnostique()->associate($diagnostique);
                    $visite->date_heure = now()->utc();
                    $visite->save();
                }

                $triage = new Triage();
                $triage->fill($data);
                $triage->save();
            });
        } catch (Throwable $e) {
            Log::error('Triage enqueue failed', [
                'error' => $e->getMessage(),
            ]);
        }

    }

    /**
     * Retire le patient courant de la file d'attente.
     *
     * @param User $user Utilisateur courant.
     * @return void
     */
    public static function dequeue(User $user): void
    {
        $patient = $user->compte->triage;

        if ($patient) {
            $patient->delete();
        }
    }

    /**
     * Rééquilibre les patients entre les personnels d'un même rôle.
     *
     * @param Roles $role Rôle ciblé (infirmier ou médecin).
     * @return void
     */
    public static function rebalance(Roles $role): void
    {
        $triages = Triage::orderBy('priority')
            ->orderBy('position')
            ->where('file_attente', $role)
            ->get();

        $personnels = Personnel::where('role', $role)
            ->with('triages') // 🔥 important
            ->get();

        if ($personnels->isEmpty()) return;

        foreach ($personnels as $p) {
            if ($p->triages->isNotEmpty()) {

                $patientSeen = $p->triages
                    ->sortBy(['priority', 'position'])
                    ->first();

                if ($patientSeen) {
                    $triages = $triages->reject(
                        fn($t) => $t->id === $patientSeen->id
                    );

                    $p->setRelation(
                        'triages',
                        $p->triages->reject(
                            fn($t) => $t->id === $patientSeen->id
                        )
                    );
                }
            }
        }

        foreach ($triages as $triage) {
            $practitioner = $personnels->sortBy(
                fn($p) => $p->triages->count()
            )->first();

            $triage->personnel_id = $practitioner->id;
            $triage->save();

            $practitioner->triages->push($triage);

            if ($triage->priority > 0) {
                $triage->compte->visites()->latest('date_heure')->first()
                    ->update(['docteur_id' => $practitioner->compte->id]);
            }
        }
    }

    /**
     * Retourne le compte patient à un index donné dans la file du personnel.
     *
     * @param int $index Index basé sur 0 dans la file triée par priorité puis position.
     * @param User $user Utilisateur courant.
     * @return Compte Compte du patient correspondant.
     * @throws OutOfBoundsException Si l'index ne peut pas être résolu.
     */
    public static function peek(int $index, User $user): Compte
    {
        $personnel = $user->compte->personnel;

        try {
            return DB::transaction(function () use ($personnel, $index) {
                $triage = Triage::orderBy('priority')
                    ->orderBy('position')
                    ->where('personnel_id', $personnel->id)
                    ->skip($index)
                    ->first();

                return $triage->compte;
            });
        } catch (Throwable $e) {
            Log::error('Triage peek failed', [
                'error' => $e->getMessage(),
            ]);

            throw new OutOfBoundsException("Index out of bounds");
        }
    }

    /**
     * Vérifie si l'utilisateur est actuellement dans une file de triage.
     *
     * @param User $user Utilisateur courant.
     * @return bool True si un triage existe pour le compte, sinon false.
     */
    public static function enqueued(User $user): bool
    {
        $compte = $user->compte;
        return Triage::where('compte_id', $compte->id)->exists();
    }


    /**
     * Retourne la position du patient dans la file du praticien assigné.
     *
     * @param User $user Utilisateur courant.
     * @return int Position (index 1) ou -1 si non trouvé/non assigné.
     */
    public static function queuePosition(User $user): int
    {
        $compte = $user->compte;
        $patient = $compte->triage;

        if ($patient && $patient->personnel_id !== null) {
            $triages = Triage::orderBy('priority')
                ->orderBy('position')
                ->where('personnel_id', $patient->personnel_id)
                ->get();

            $index = 1;


            foreach ($triages as $triage) {
                if ($triage->compte_id == $compte->id) {
                    return $index;
                }
                $index++;
            }
        }

        return -1;
    }

}
