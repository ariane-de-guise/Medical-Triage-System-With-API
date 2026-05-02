<?php

namespace App\Http\Controllers\Admin;

use App\Models\Compte;
use App\Models\Telephone;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Gère les téléphones associés aux comptes dans l'API d'administration.
 */
class TelephoneController extends Controller
{
    /**
     * Liste les téléphones d'un compte avec filtres optionnels.
     *
     * @param Request $request Critères de filtrage.
     * @param int|string $compte_id Identifiant du compte.
     * @return JsonResponse
     */
    public function index(Request $request, int|string $compte_id)
    {
        $compte = Compte::find($compte_id);

        if (!$compte) {
            return response()->json(['message' => 'Le compte n\'existe pas.'], 404);
        }

        $query = $compte->telephones();

        if ($request->filled('created_at')) {
            $query->where('telephones.created_at', '>=', $request->created_at);
        }

        if ($request->filled('contains')) {
            $number = $request->contains;

            $query->where(function ($q) use ($number) {
                $q->where('numero', 'like', "%$number%");
            });
        }

        $telephones = $query->get();

        return response()->json([
            'telephones' => $telephones,
        ]);
    }

    /**
     * Crée un téléphone et l'associe au compte indiqué.
     *
     * @param Request $request Données de création.
     * @param int|string $compte_id Identifiant du compte.
     * @return JsonResponse
     */
    public function store(Request $request, int|string $compte_id)
    {
        try {
            $request->validate([
                'telephone' => ['required', 'numeric', 'min:1', 'max:10000000000'],
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        $compte = Compte::find($compte_id);
        if (!$compte) {
            return response()->json([
                'message' => 'Le compte n\'existe pas',
            ], 400);
        }

        try {
            $telephone = Telephone::create([
                'numero' => $request->telephone,
            ]);

            $compte->telephones()->attach($telephone->id);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la création du téléphone: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Téléphone ajouté avec succès',
        ], 201);
    }

    /**
     * Affiche un téléphone précis d'un compte.
     *
     * @param int|string $compte_id Identifiant du compte.
     * @param int|string $id Identifiant du téléphone.
     * @return JsonResponse
     */
    public function show(int|string $compte_id, int|string $id)
    {
        $compte = Compte::find($compte_id);

        if (!$compte) {
            return response()->json(['message' => 'Le compte n\'existe pas.'], 404);
        }

        $telephones = $compte->telephones;
        $telephone = $telephones->where('id', $id)->first();

        if (!$telephone) {
            return response()->json(['message' => 'Le téléphone n\'existe pas'], 404);
        }

        return response()->json([
            'telephone' => $telephone,
        ]);
    }

    /**
     * Retourne un téléphone pour l'écran d'édition.
     *
     * @param int|string $compte_id Identifiant du compte.
     * @param int|string $id Identifiant du téléphone.
     * @return JsonResponse
     */
    public function edit(int|string $compte_id, int|string $id)
    {
        $compte = Compte::find($compte_id);

        if (!$compte) {
            return response()->json(['message' => 'Le compte n\'existe pas.'], 404);
        }

        $telephones = $compte->telephones;
        $telephone = $telephones->where('id', $id)->first();

        if (!$telephone) {
            return response()->json(['message' => 'Le téléphone n\'existe pas'], 404);
        }

        return response()->json([
            'telephone' => $telephone,
        ]);
    }

    /**
     * Met à jour un téléphone associé à un compte.
     *
     * @param int|string $compte_id Identifiant du compte.
     * @param Request $request Données de mise à jour.
     * @return JsonResponse
     */
    public function update(int|string $compte_id, Request $request)
    {
        try {
            $request->validate([
                'telephone_id' => ['required', 'exists:telephones,id'],
                'telephone' => ['required', 'numeric', 'min:1', 'max:10000000000'],
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        $compte = Compte::find($compte_id);

        if (!$compte) {
            return response()->json(['message' => 'Le compte n\'existe pas.'], 404);
        }

        $telephone = $compte->telephones->where('id', $request->telephone_id)->first();

        if (!$telephone) {
            return response()->json([
                'message' => 'L\'adresse n\'existe pas',
            ], 404);
        }

        try {
            $telephone->update([
                'numero' => $request->telephone,
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Le téléphone n\'a pas pu être modifié : ' . $e->getMessage()], 400);
        }

        return response()->json([["message" => "Le téléphone a été modfié."], 200]);
    }

    /**
     * Supprime un téléphone d'un compte.
     *
     * @param int|string $compte_id Identifiant du compte.
     * @param int|string $id Identifiant du téléphone.
     * @return JsonResponse
     */
    public function destroy(int|string $compte_id, int|string $id)
    {
        $compte = Compte::find($compte_id);

        if (!$compte) {
            return response()->json(['message' => 'Le compte n\'existe pas.'], 404);
        }

        $telephone = $compte->telephones->where('id', $id)->first();

        if (!$telephone) {
            return response()->json([
                'message' => 'Le téléphone n\'existe pas',
            ], 404);
        }

        try {
            $telephone->delete();
        } catch (Exception $e) {
            return response()->json(['message' => 'Ce téléphone n\'a pas pu être supprimé : ' . $e->getMessage()], 400);
        }

        return response()->json([["message" => "Le téléphone a été supprimé."], 200]);
    }
}
