<?php

namespace App\Http\Controllers\Admin;

use App\Models\Compte;
use App\Models\Telephone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TelephoneController extends Controller
{
    public function index(Request $request, $compte_id)
    {
        $compte = Compte::find($compte_id);

        if (!$compte) {
            return response()->json(['message' => 'Le compte n\'existe pas.'], 404);
        }

        $query = $compte->telephones();

        if ($request->filled('created_at')) {
            $query->where('created_at', '>=', $request->created_at);
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

    public function store(Request $request, $compte_id)
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

    public function show($compte_id, $id)
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

    public function edit($compte_id, $id)
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

    public function update($compte_id, Request $request)
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

        $telephone= $compte->telephones->where('id', $request->telephone_id)->first();

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

    public function destroy($compte_id, $id)
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
