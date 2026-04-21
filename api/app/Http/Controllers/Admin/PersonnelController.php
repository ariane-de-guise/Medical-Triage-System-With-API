<?php

namespace App\Http\Controllers\Admin;

use App\Algorithms\Roles;
use App\Algorithms\TimeCard;
use App\Models\Compte;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PersonnelController extends Controller
{
    public function index(Request $request) {
        $query = Personnel::query();

        if ($request->filled('role')) {
            $query->whereHas('compte.user', function ($q) use ($request) {
                $q->role($request->role);
            });
        }

        if ($request->filled('temps_connexion')) {
            $hours = (int) $request->temps_connexion;
            $query->where('created_at', '<=', now()->subHours($hours));
        }

        $personnels = $query->get()->load('compte.user');

        return response()->json($personnels);
    }

    public function create() {
        $personnels = Compte::query()->whereHas('user', function ($q) {
                $q->role([Roles::Medecin, Roles::Infirmier]);
            })
            ->whereDoesntHave('personnel')
            ->with('user.roles')
            ->get();

        return response()->json($personnels);
    }

    public function store(Request $request) {
        $request->validate([
            'compte_id' => 'required|exists:comptes,id',
            'role' => 'required|exists:roles,name',
        ]);

        $compte = Compte::with('user.roles')->find($request->compte_id);

        if (!$compte) {
            return response()->json([
                'message' => 'Le compte n\'existe pas',
            ], 404);
        }

        if (!$compte->user->roles->contains('name', $request->role)) {
            return response()->json(['message' => 'Ce rôle n’appartient pas à cet utilisateur'], 403);
        }

        TimeCard::login($compte->user, Roles::from($request->role));

        return response()->json([
            'message' => 'Personnel créé avec succès',
        ], 201);
    }

    public function show($id)
    {
        $query = Personnel::find($id);

        if (!$query) {
            return response()->json([
                'message' => 'Le personnel n\'existe pas',
            ], 404);
        }

        $personnel = $query->load('compte.user');

        return response()->json($personnel);
    }

    public function edit($id) {
        $query = Personnel::find($id);

        if (!$query) {
            return response()->json([
                'message' => 'Le personnel n\'existe pas',
            ], 404);
        }

        $personnel = $query->load('compte.user');

        if ($personnel->compte->user->roles->pluck('name')->count() == 1) {
            return response()->json([
                'message' => 'Vous ne pouvez pas modifier ce personnel',
            ], 404);
        }

        return response()->json([
            'personnel' => $personnel,
            'roles' => $personnel->compte->user->roles->pluck('name')->toArray(),
        ]);
    }

    public function update(Request $request) {
        $request->validate([
            'personnel_id' => 'required|exists:personnels,id',
            'role' => 'required|exists:roles,name',
        ]);

        $query = Personnel::find($request->personnel_id);

        if (!$query) {
            return response()->json(["message" => "Le personnel n'existe pas"], 404);
        }

        $personnel = $query->load('compte.user.roles');
        $compte = $personnel->compte;

        if (!$compte->user->roles->contains('name', $request->role)) {
            return response()->json(['message' => 'Ce rôle n’appartient pas à cet utilisateur'], 403);
        }


        if ($query->role === $request->role) {
            return response()->json(['message' => 'Le rôle ne peut pas être le même.'], 400);
        }

        TimeCard::logout($compte->user, $query->role);
        TimeCard::login($compte->user, Roles::from($request->role));

        return response()->json([["message" => "Le personnel a été modfié."], 200]);
    }

    public function destroy($id) {
        $query = Personnel::find($id);

        if (!$query) {
            return response()->json(["message" => "Le personnel n'existe pas"], 404);
        }

        TimeCard::logout($query->compte->user, $query->role);
        return response()->json([["message" => "Le personnel a été supprimé."], 200]);
    }
}
