<?php

namespace App\Http\Controllers;

use App\Algorithms\Roles;
use App\Algorithms\TimeCard;
use App\Algorithms\TriageQueue;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MedecinController extends Controller
{
    public function index($id) {
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

    public function show($id) {
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

    public function store($id) {
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

    public function destroy($id) {
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

    public function update($id, Request $request) {
        $request->validate([
            'diagnostique' => 'required',
            'date_heure' => 'required|date',
        ]);

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
