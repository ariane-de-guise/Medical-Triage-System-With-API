<?php

namespace App\Http\Controllers;

use App\Algorithms\TriageQueue;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Expose les endpoints API disponibles pour les patients.
 */
class PatientController extends Controller
{
    /**
     * Retourne le profil patient enrichi et sa position dans la file de triage.
     *
     * @param int|string $id Identifiant utilisateur.
     * @return JsonResponse
     */
    public function index(int|string $id)
    {
        $user = User::with(['compte'])->findOrFail($id);
        $user->load(['compte.visites', 'compte.visites.diagnostique', 'compte.visites.docteur']);

        $position = TriageQueue::queuePosition($user);

        return response()->json([
            'user' => $user,
            'position' => $position
        ]);
    }

    /**
     * Retire le patient de la file d'attente s'il y est inscrit.
     *
     * @param int|string $id Identifiant utilisateur.
     * @return JsonResponse
     */
    public function destroy(int|string $id)
    {
        $user = User::findOrFail($id);

        $triage = $user->compte->triage;

        if ($triage) {
            $triage->delete();
        } else {
            return response()->json([
                'message' => 'Vous n\'êtes pas dans la file d\'attente',
            ], 400);
        }

        return response()->json([
            'message' => 'File retirée avec succès',
        ]);
    }

    /**
     * Ajoute le patient à la file d'attente infirmière.
     *
     * @param int|string $id Identifiant utilisateur.
     * @return JsonResponse
     */
    public function store(int|string $id)
    {
        $user = User::findOrFail($id);

        if (TriageQueue::enqueued($user)) {
            return response()->json([
                'message' => 'Vous êtes déjà dans la file d\'attente',
            ], 400);
        }

        TriageQueue::enqueue($user, 0, "");

        return response()->json([
            'message' => 'Vous avez été ajouté à la file d\'attente',
        ]);
    }
}
