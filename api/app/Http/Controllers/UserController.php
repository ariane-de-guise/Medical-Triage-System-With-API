<?php

namespace App\Http\Controllers;

use App\Algorithms\TriageQueue;
use App\Models\User;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::with(['compte'])->findOrFail($id);
        $user->load(['compte.adresses', 'compte.telephones']);

        return response()->json([
            'user' => $user,
        ]);
    }

    public function patient($id) {
        $user = User::with(['compte'])->findOrFail($id);
        $user->load(['compte.visites', 'compte.visites.diagnostique', 'compte.visites.docteur']);

        $position = TriageQueue::queuePosition($user);

        return response()->json([
            'user' => $user,
            'position' => $position
        ]);
    }

    public function retireFile($id)
    {
        $user = User::findOrFail($id);

        $triage = $user->compte->triage;

        if ($triage) {
            $triage->delete();
        }
        else {
            return response()->json([
                'message' => 'Vous n\'êtes pas dans la file d\'attente',
            ], 400);
        }

        return response()->json([
            'message' => 'File retirée avec succès',
        ]);
    }

    public function ajoutFile($id) {
        $user = User::findOrFail($id);

        if (TriageQueue::enqueued($user)) {
            return response()->json([
                'message' => 'Vous êtes déjà dans la file d\'attente',
            ], 400);
        }

        TriageQueue::enqueue($user, 0);

        return response()->json([
            'message' => 'Vous avez été ajouté à la file d\'attente',
        ]);
    }
}
