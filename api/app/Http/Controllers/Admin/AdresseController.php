<?php

namespace App\Http\Controllers\Admin;

use App\Algorithms\Constants;
use App\Models\Adresse;
use App\Models\Compte;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Gère les adresses associées aux comptes dans l'API d'administration.
 */
class AdresseController
{
    /**
     * Liste les adresses d'un compte avec filtres optionnels.
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

        $query = $compte->adresses();

        if ($request->filled('province')) {
            $query->where('province', '=', $request->province);
        }

        if ($request->filled('type')) {
            $query->where('type', '=', $request->type);
        }

        $adresses = $query->get();
        $provinces = array_map(fn($province) => ['name' => $province], Constants::PROVINCES);
        $types = array_map(fn($type) => ['name' => $type], Constants::TYPES);

        return response()->json([
            'adresses' => $adresses,
            'provinces' => $provinces,
            'types' => $types,
        ]);
    }

    /**
     * Retourne les métadonnées nécessaires à la création d'une adresse.
     *
     * @return JsonResponse
     */
    public function create()
    {
        $provinces = array_map(fn($province) => ['name' => $province], Constants::PROVINCES);
        $types = array_map(fn($type) => ['name' => $type], Constants::TYPES);

        return response()->json([
            'provinces' => $provinces,
            'types' => $types,
        ]);
    }

    /**
     * Crée une adresse et l'associe au compte indiqué.
     *
     * @param Request $request Données d'adresse.
     * @param int|string $compte_id Identifiant du compte.
     * @return JsonResponse
     */
    public function store(Request $request, int|string $compte_id)
    {
        try {
            $request->validate([
                'numero' => ['required', 'numeric', 'min:1', 'max:1000000000'],
                'rue' => ['required', 'string', 'max:255', 'min:1'],
                'ville' => ['required', 'string', 'max:255', 'min:1'],
                'province' => ['required', 'string', 'size:2', Rule::in(Constants::PROVINCES)],
                'code_postal' => ['required', 'string', 'size:6', 'regex:/^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$/'],
                'type' => ['required', 'string', Rule::in(Constants::TYPES)],
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
            $adresse = Adresse::create([
                'numero' => $request->numero,
                'rue' => $request->rue,
                'ville' => $request->ville,
                'province' => $request->province,
                'code_postal' => $request->code_postal,
                'type' => $request->type,
            ]);

            $compte->adresses()->attach($adresse->id);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la création de l\'adresse: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Adresse ajoutée avec succès',
        ], 201);
    }

    /**
     * Affiche une adresse précise d'un compte.
     *
     * @param int|string $compte_id Identifiant du compte.
     * @param int|string $id Identifiant de l'adresse.
     * @return JsonResponse
     */
    public function show(int|string $compte_id, int|string $id)
    {
        $compte = Compte::find($compte_id);

        if (!$compte) {
            return response()->json(['message' => 'Le compte n\'existe pas.'], 404);
        }

        $adresses = $compte->adresses;
        $adresse = $adresses->where('id', $id)->first();

        if (!$adresse) {
            return response()->json(['message' => 'L\'adresse n\'existe pas'], 404);
        }

        return response()->json([
            'adresse' => $adresse,
        ]);
    }

    /**
     * Retourne une adresse et les métadonnées pour son édition.
     *
     * @param int|string $compte_id Identifiant du compte.
     * @param int|string $id Identifiant de l'adresse.
     * @return JsonResponse
     */
    public function edit(int|string $compte_id, int|string $id)
    {
        $compte = Compte::find($compte_id);

        if (!$compte) {
            return response()->json(['message' => 'Le compte n\'existe pas.'], 404);
        }

        $adresses = $compte->adresses;
        $adresse = $adresses->where('id', $id)->first();

        if (!$adresse) {
            return response()->json(['message' => 'L\'adresse n\'existe pas'], 404);
        }

        $provinces = array_map(fn($province) => ['name' => $province], Constants::PROVINCES);
        $types = array_map(fn($type) => ['name' => $type], Constants::TYPES);

        return response()->json([
            'adresse' => $adresse,
            'provinces' => $provinces,
            'types' => $types,
        ]);
    }

    /**
     * Met à jour une adresse liée à un compte.
     *
     * @param int|string $compte_id Identifiant du compte.
     * @param Request $request Données de mise à jour.
     * @return JsonResponse
     */
    public function update(int|string $compte_id, Request $request)
    {
        try {
            $request->validate([
                'adresse_id' => ['required', 'exists:adresses,id'],
                'numero' => ['required', 'numeric', 'min:1', 'max:1000000000'],
                'rue' => ['required', 'string', 'max:255', 'min:1'],
                'ville' => ['required', 'string', 'max:255', 'min:1'],
                'province' => ['required', 'string', 'size:2', Rule::in(Constants::PROVINCES)],
                'code_postal' => ['required', 'string', 'size:6', 'regex:/^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$/'],
                'type' => ['required', 'string', Rule::in(Constants::TYPES)],
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        $compte = Compte::find($compte_id);

        if (!$compte) {
            return response()->json(['message' => 'Le compte n\'existe pas.'], 404);
        }

        $adresse = $compte->adresses->where('id', $request->adresse_id)->first();

        if (!$adresse) {
            return response()->json([
                'message' => 'L\'adresse n\'existe pas',
            ], 404);
        }

        try {
            $adresse->update([
                'numero' => $request->numero,
                'rue' => $request->rue,
                'ville' => $request->ville,
                'province' => $request->province,
                'code_postal' => $request->code_postal,
                'type' => $request->type,
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'L\'adresse n\'a pas pu être modifiée : ' . $e->getMessage()], 400);
        }

        return response()->json([["message" => "L'adresse a été modfiée."], 200]);
    }

    /**
     * Supprime une adresse d'un compte.
     *
     * @param int|string $compte_id Identifiant du compte.
     * @param int|string $id Identifiant de l'adresse.
     * @return JsonResponse
     */
    public function destroy(int|string $compte_id, int|string $id)
    {
        $compte = Compte::find($compte_id);

        if (!$compte) {
            return response()->json(['message' => 'Le compte n\'existe pas.'], 404);
        }

        $adresse = $compte->adresses->where('id', $id)->first();

        if (!$adresse) {
            return response()->json([
                'message' => 'L\'adresse n\'existe pas',
            ], 404);
        }

        try {
            $adresse->delete();
        } catch (Exception $e) {
            return response()->json(['message' => 'Cette adresse n\'a pas pu être supprimée : ' . $e->getMessage()], 400);
        }

        return response()->json([["message" => "L'adresse' a été supprimée."], 200]);
    }
}
