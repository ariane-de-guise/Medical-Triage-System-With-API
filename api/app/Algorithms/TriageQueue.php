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

class TriageQueue
{
    /**
     * Fonction pour recevoir la taille complète de sa file d'attente en tant que médecin ou infirmier.
     *
     * @param User $user Utilisateur de la session.
     * @return int Taille de la file d'attente du personnel du compte de l'utilisateur.
     */
    public static function queueCount(User $user): int {
        $compte = $user->compte;
        $personnel  = $compte->personnel;
        if (!$personnel) {
            return 0;
        }
        return $personnel->triages()->count();
    }

    /**
     * Fonction pour s'ajouter à une file d'attente (médecin ou infirmier).
     *
     * @param User $user Utilisateur de la session.
     * @param int $priority Priorité de l'urgence (0 pour infirmier).
     * @return void Exception si problème à s'ajouter à la file.
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
     * Fonction pour se retirer de la file d'attente.
     *
     * @param User $user Utilisateur de la session.
     * @return void Exception si problème à se retirer de la file d'attente.
     */
    public static function dequeue(User $user): void
    {
        $patient = $user->compte->triage;

        if ($patient) {
            $patient->delete();
        }
    }

    /**
     * Fonction pour répartir les patients lors de login ou logout.
     *
     * @param Roles $role Infirmier ou Médecin, selon la file d'attente à rééquilibrer.
     * @return void Exception si problème à rebalancer la file d'attente.
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
                        fn ($t) => $t->id === $patientSeen->id
                    );

                    $p->setRelation(
                        'triages',
                        $p->triages->reject(
                            fn ($t) => $t->id === $patientSeen->id
                        )
                    );
                }
            }
        }

        foreach ($triages as $triage) {
            $practitioner = $personnels->sortBy(
                fn ($p) => $p->triages->count()
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
     * Fonction pour avoir le compte du patient à une certaine position dans sa file.
     *
     * @param int $index Index de la file dont on veut peek.
     * @param User $user Utilisateur de la session.
     * @return Compte Compte du patient.
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
     * Fonction pour vérifier si on est dans une file.
     *
     * @param User $user Utilisateur de la session
     * @return bool True si le patient est dans une file d'attente, false sinon.
     */
    public static function enqueued(User $user): bool {
        $compte = $user->compte;
        return Triage::where('compte_id', $compte->id)->exists();
    }


    /**
     * Fonction pour connaître sa position dans la file d'attente.
     *
     * @param User $user Utilisateur de la session.
     * @return int Position dans la file.
     */
    public static function queuePosition(User $user): int {
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
