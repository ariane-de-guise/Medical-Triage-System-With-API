<?php

namespace App\Http\Controllers\Admin;

use App\Algorithms\Roles;
use App\Algorithms\TriageQueue;
use App\Models\Compte;
use App\Models\Triage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TriageController extends Controller
{
    public function index(Request $request) {
        $query = Triage::all();

        if ($request->filled('file_attente')) {
            $query = $query->where('file_attente', $request->file_attente);
        }

        if ($request->filled('priorite')) {
            $query = $query->where('priority', $request->priorite);
        }

        $triages = $query->load('compte', 'personnel.compte');

        return response()->json($triages);
    }

    public function create() {
        $comptes = Compte::query()->whereHas('user', function ($q) {
            $q->role(Roles::Patient);
        })
            ->whereDoesntHave('triage')
            ->with('user.roles')
            ->get();

        $files = [Roles::Infirmier->value, Roles::Medecin->value];

        return response()->json([
            'comptes' => $comptes,
            'files' => $files
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'file' => 'required|exists:roles,name',
            'priorite' => 'required|in:0,1,2,3,4,5,6,7,8,9',
        ]);

        $compte = Compte::with('user.roles')->find($request->compte_id);

        if (!$compte) {
            return response()->json([
                'message' => 'Le compte n\'existe pas',
            ], 404);
        }

        if (!$compte->user->roles->contains('name', Roles::Patient->value)) {
            return response()->json(['message' => 'Ce rôle n’appartient pas à cet utilisateur'], 403);
        }

        if ($request->priorite == 0 && $request->file != Roles::Infirmier->value) {
            return response()->json(['message' => 'La priorite doit être suppérieure à 0'], 400);
        }

        if ($request->priorite > 0 && $request->file != Roles::Medecin->value) {
            return response()->json(['message' => 'La priorite doit être égale à 0'], 400);
        }

        TriageQueue::enqueue($compte->user, $request->priorite, "Enqueue via admin");

        return response()->json([
            'message' => 'Compte ajouté au triage avec succès',
        ], 201);
    }

    public function show($id)
    {
        $query = Triage::find($id);

        if (!$query) {
            return response()->json([
                'message' => 'L\'élément de n\'existe pas',
            ], 404);
        }

        $triage = $query->load('compte', 'personnel.compte');

        return response()->json($triage);
    }

    public function edit($id) {
        $query = Triage::find($id);

        if (!$query) {
            return response()->json([
                'message' => 'L\'élément de triage n\'existe pas',
            ], 404);
        }

        $triage = $query->load('compte', 'personnel.compte');
        $files = [Roles::Infirmier->value, Roles::Medecin->value];

        return response()->json([
            'triage' => $triage,
            'files' => $files,
        ]);
    }

    public function update(Request $request) {
        $request->validate([
            'triage_id' => 'required|exists:triages,id',
            'file' => 'required|exists:roles,name',
            'priorite' => 'required|in:0,1,2,3,4,5,6,7,8,9',
        ]);

        $query = Triage::find($request->triage_id);

        if (!$query) {
            return response()->json(["message" => "L\'élément de triage n'existe pas"], 404);
        }

        $triage = $query->load('compte.user.roles');
        $compte = $triage->compte;

        if (!$compte->user->roles->contains('name', Roles::Patient)) {
            return response()->json(['message' => 'Ce compte n’est pas à un patient'], 403);
        }

        if ($query->file_attente === $request->file) {
            return response()->json(['message' => 'La file d\'attente ne peut pas être la même.'], 400);
        }

        if ($request->priorite == 0 && $request->file != Roles::Infirmier->value) {
            return response()->json(['message' => 'La priorite doit être suppérieure à 0'], 400);
        }

        if ($request->priorite > 0 && $request->file != Roles::Medecin->value) {
            return response()->json(['message' => 'La priorite doit être égale à 0'], 400);
        }

        TriageQueue::dequeue($compte->user);
        TriageQueue::enqueue($compte->user, $request->priorite, "Reenqueue via admin");

        return response()->json([["message" => "L'élément de triage a été modfié."], 200]);
    }

    public function destroy($id) {
        $query = Triage::find($id);

        if (!$query) {
            return response()->json(["message" => "L'élément de triage n'existe pas"], 404);
        }

        TriageQueue::dequeue($query->compte->user);
        return response()->json([["message" => "L'élément de triage a été supprimé."], 200]);
    }
}
