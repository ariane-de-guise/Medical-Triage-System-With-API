<?php

namespace App\Http\Controllers\Admin;

use App\Algorithms\Constants;
use App\Models\Adresse;
use App\Models\Compte;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdresseController
{
    public function index(Request $request, $compte_id)
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

    public function create()
    {
        $provinces = array_map(fn($province) => ['name' => $province], Constants::PROVINCES);
        $types = array_map(fn($type) => ['name' => $type], Constants::TYPES);

        return response()->json([
            'provinces' => $provinces,
            'types' => $types,
        ]);
    }

    public function store(Request $request, $compte_id)
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

        $compte  = Compte::find($compte_id);
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

    public function show($compte_id, $id)
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

    public function edit($compte_id, $id)
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

    public function update($compte_id, Request $request)
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

    public function destroy($compte_id, $id)
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
