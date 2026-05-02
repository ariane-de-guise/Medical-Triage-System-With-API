<?php

namespace App\Http\Controllers\Admin;

use App\Algorithms\Roles;
use App\Algorithms\TimeCard;
use App\Models\Compte;
use App\Models\Personnel;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Gère le personnel clinique via l'API d'administration.
 */
class PersonnelController extends Controller
{
    /**
     * Liste le personnel avec filtres de rôle et de durée de connexion.
     *
     * @param Request $request Critères de filtrage.
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $query = Personnel::query();

        if ($request->filled('role')) {
            $query->whereHas('compte.user', function ($q) use ($request) {
                $q->role($request->role);
            });
        }

        if ($request->filled('temps_connexion')) {
            $hours = (int)$request->temps_connexion;
            $query->where('created_at', '<=', now()->subHours($hours));
        }

        $personnels = $query->get()->load('compte.user');

        return response()->json($personnels);
    }

    /**
     * Retourne les comptes admissibles à la création d'un enregistrement de personnel.
     *
     * @return JsonResponse
     */
    public function create()
    {
        $personnels = Compte::query()->whereHas('user', function ($q) {
            $q->role([Roles::Medecin, Roles::Infirmier]);
        })
            ->whereDoesntHave('personnel')
            ->with('user.roles')
            ->get();

        return response()->json($personnels);
    }

    /**
     * Crée une connexion de personnel pour un compte et un rôle donné.
     *
     * @param Request $request Données de création.
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'compte_id' => 'required|exists:comptes,id',
                'role' => 'required|exists:roles,name',
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

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

    /**
     * Affiche un membre du personnel.
     *
     * @param int|string $id Identifiant du personnel.
     * @return JsonResponse
     */
    public function show(int|string $id)
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

    /**
     * Retourne les données d'un personnel et les rôles modifiables.
     *
     * @param int|string $id Identifiant du personnel.
     * @return JsonResponse
     */
    public function edit(int|string $id)
    {
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

    /**
     * Modifie le rôle actif d'un personnel.
     *
     * @param Request $request Données de mise à jour.
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        try {
            $request->validate([
                'personnel_id' => 'required|exists:personnels,id',
                'role' => 'required|exists:roles,name',
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

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

    /**
     * Supprime un membre du personnel en le déconnectant du triage.
     *
     * @param int|string $id Identifiant du personnel.
     * @return JsonResponse
     */
    public function destroy(int|string $id)
    {
        $query = Personnel::find($id);

        if (!$query) {
            return response()->json(["message" => "Le personnel n'existe pas"], 404);
        }

        TimeCard::logout($query->compte->user, $query->role);
        return response()->json([["message" => "Le personnel a été supprimé."], 200]);
    }
}
