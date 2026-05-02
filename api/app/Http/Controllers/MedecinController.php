<?php

namespace App\Http\Controllers;

use App\Algorithms\Roles;
use App\Algorithms\TimeCard;
use App\Algorithms\TriageQueue;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Expose les opérations API utilisées par le personnel médecin.
 */
class MedecinController extends Controller
{
    /**
     * Retourne l'état de connexion du médecin et la liste de ses patients en file.
     *
     * @param int|string $id Identifiant utilisateur.
     * @return JsonResponse
     */
    public function index(int|string $id)
    {
        $user = User::findOrFail($id);
        $count = TriageQueue::queueCount($user);
        $patients = [];
        $loggedin = TimeCard::loggedin($user, Roles::Medecin);

        for ($i = 0; $i < $count; ++$i) {
            $patients[] = TriageQueue::peek($i, $user)->load([
                'triage',
                'latestVisite',
                'latestVisite.diagnostique'
            ]);
        }

        return response()->json([
            'loggedIn' => $loggedin,
            'count' => TriageQueue::queueCount($user),
            'patients' => $patients,
        ]);
    }

    /**
     * Retourne le prochain patient à traiter pour le médecin.
     *
     * @param int|string $id Identifiant utilisateur.
     * @return JsonResponse
     */
    public function show(int|string $id)
    {
        $user = User::findOrFail($id);
        $patient = TriageQueue::peek(0, $user)->load([
            'triage',
            'latestVisite',
            'latestVisite.diagnostique'
        ]);

        return response()->json([
            'patient' => $patient,
            'loggedIn' => TimeCard::loggedin($user, Roles::Medecin),
        ]);
    }

    /**
     * Connecte l'utilisateur courant au système de triage comme médecin.
     *
     * @param int|string $id Identifiant utilisateur.
     * @return JsonResponse
     */
    public function store(int|string $id)
    {
        $user = User::findOrFail($id);
        try {
            if (TimeCard::loggedin($user, Roles::Infirmier) || TimeCard::loggedin($user, Roles::Medecin)) {
                return response()->json(['message' => 'Vous êtes déjà connecté au système de triage'], 400);
            }
            TimeCard::login($user, Roles::Medecin);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        return response()->json([
            'message' => 'Connexion au système de triage réussie',
        ]);
    }

    /**
     * Déconnecte le médecin du système de triage.
     *
     * @param int|string $id Identifiant utilisateur.
     * @return JsonResponse
     */
    public function destroy(int|string $id)
    {
        $user = User::findOrFail($id);
        try {
            TimeCard::logout($user, Roles::Medecin);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response()->json([
            'message' => 'Déconnexion du système de triage réussie',
        ]);
    }

    /**
     * Met à jour le diagnostique du patient courant puis retire ce patient de la file.
     *
     * @param int|string $id Identifiant utilisateur.
     * @param Request $request Données du diagnostique.
     * @return JsonResponse
     */
    public function update(int|string $id, Request $request)
    {
        try {
            $request->validate([
                'diagnostique' => 'required',
                'date_heure' => 'required|date',
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        $user = User::findOrFail($id);

        $patient = TriageQueue::peek(0, $user);

        $visite = $patient->latestVisite;

        if (!$visite || !$visite->diagnostique) {
            return response()->json([
                'message' => 'Aucun diagnostique trouvé pour ce patient'
            ], 404);
        }

        $diagnostique = $visite->diagnostique;

        $diagnostique->update([
            'description' => $request->input('diagnostique'),
            'date_heure' => Carbon::parse($request->input('date_heure'))
                ->format('Y-m-d H:i:s'),
        ]);

        TriageQueue::dequeue($patient->user);

        return response()->json([
            'message' => 'Diagnostique mis à jour avec succès',
        ]);
    }
}
