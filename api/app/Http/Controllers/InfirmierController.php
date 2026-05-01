<?php

namespace App\Http\Controllers;

use App\Algorithms\AlgorithmePriorite;
use App\Algorithms\Roles;
use App\Algorithms\Symptomes;
use App\Algorithms\TimeCard;
use App\Algorithms\TriageQueue;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class InfirmierController extends Controller
{
    public function index($id)
    {
        $user = User::findOrFail($id);
        $count = TriageQueue::queueCount($user);
        $patients = [];
        $loggedin = TimeCard::loggedin($user, Roles::Infirmier);

        for ($i = 0; $i < $count; ++$i) {
            $patients[] = TriageQueue::peek($i, $user)->load([
                'triage'
            ]);
        }

        return response()->json([
            'loggedIn' => $loggedin,
            'count' => TriageQueue::queueCount($user),
            'patients' => $patients,
        ]);
    }

    public function show($id, Request $request)
    {
        $user = User::findOrFail($id);
        $patient = TriageQueue::peek(0, $user)->load([
            'triage'
        ]);

        $symptomes = array_map(
            fn(Symptomes $symptome) => mb_convert_case($symptome->value, MB_CASE_TITLE, 'UTF-8'),
            Symptomes::cases()
        );

        if ($request->symptomes) {
            $priorite = AlgorithmePriorite::calculer($request->symptomes);
            $symptomes = implode(', ', $request->symptomes);
        }

        return response()->json([
            'patient' => $patient,
            'loggedIn' => TimeCard::loggedin($user, Roles::Infirmier),
            'priorite' => $priorite ?? null,
            'symptomes' => $symptomes,
        ]);
    }

    public function store($id)
    {
        $user = User::findOrFail($id);
        try {
            if (TimeCard::loggedin($user, Roles::Infirmier) || TimeCard::loggedin($user, Roles::Medecin)) {
                return response()->json(['message' => 'Vous êtes déjà connecté au système de triage'], 400);
            }
            TimeCard::login($user, Roles::Infirmier);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        return response()->json([
            'message' => 'Connexion au système de triage réussie',
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        try {
            TimeCard::logout($user, Roles::Infirmier);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response()->json([
            'message' => 'Déconnexion du système de triage réussie',
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $request->validate([
                'commentaire' => 'required|string',
                'priorite' => 'required|integer',
                'symptomes' => 'required|string',
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        $user = User::findOrFail($id);
        $patient = TriageQueue::peek(0, $user);

        $commentaire = $request->commentaire;
        $priorite = $request->priorite;
        $symptomes = $request->symptomes;

        try {
            TriageQueue::dequeue($patient->user);
            TriageQueue::enqueue($patient->user, $priorite, ($commentaire . ' --- Symptômes : ' . $symptomes));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response()->json([
            'message' => 'Vous avez terminé cette consultation.',
        ]);
    }
}
