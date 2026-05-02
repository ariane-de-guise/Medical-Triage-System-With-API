<?php

namespace App\Http\Controllers\Admin;

use App\Algorithms\Roles;
use App\Algorithms\TriageQueue;
use App\Models\Compte;
use App\Models\Triage;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Gère les éléments de triage via l'API d'administration.
 */
class TriageController extends Controller
{
    /**
     * Liste les éléments de triage avec filtres optionnels.
     *
     * @param Request $request Critères de filtrage.
     * @return JsonResponse
     */
    public function index(Request $request)
    {
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

    /**
     * Retourne les patients admissibles et les files disponibles pour la création.
     *
     * @return JsonResponse
     */
    public function create()
    {
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

    /**
     * Ajoute un patient à une file de triage en administration.
     *
     * @param Request $request Données de création du triage.
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'compte_id' => 'required|exists:comptes,id',
                'file' => 'required|exists:roles,name',
                'priorite' => 'required|in:0,1,2,3,4,5',
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

    /**
     * Affiche un élément de triage précis.
     *
     * @param int|string $id Identifiant du triage.
     * @return JsonResponse
     */
    public function show(int|string $id)
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

    /**
     * Retourne les données d'un triage pour son édition.
     *
     * @param int|string $id Identifiant du triage.
     * @return JsonResponse
     */
    public function edit(int|string $id)
    {
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

    /**
     * Met à jour une entrée de triage en réassignant le patient.
     *
     * @param Request $request Données de mise à jour.
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        try {
            $request->validate([
                'triage_id' => 'required|exists:triages,id',
                'file' => 'required|exists:roles,name',
                'priorite' => 'required|in:0,1,2,3,4,5',
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

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

    /**
     * Supprime un élément de triage.
     *
     * @param int|string $id Identifiant du triage.
     * @return JsonResponse
     */
    public function destroy(int|string $id)
    {
        $query = Triage::find($id);

        if (!$query) {
            return response()->json(["message" => "L'élément de triage n'existe pas"], 404);
        }

        TriageQueue::dequeue($query->compte->user);
        return response()->json([["message" => "L'élément de triage a été supprimé."], 200]);
    }
}
