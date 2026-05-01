<?php

namespace App\Http\Controllers\Admin;

use App\Algorithms\Roles;
use App\Algorithms\Symptomes;
use App\Models\Diagnostique;
use App\Models\User;
use App\Models\Visite;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class VisiteController extends Controller
{
    public function index(Request $request, $compte_id)
    {
        $query = Visite::query();
        $query = $query->where('patient_id', '=', $compte_id);

        if ($request->filled('date_heure')) {
            $query->where('date_heure', '>=', $request->date_heure);
        }

        if ($request->filled('docteur_id')) {
            $query->where('docteur_id', '=', $request->docteur_id);
        }

        $visites = $query->get()->load('diagnostique', 'docteur');
        $docteurs = User::role(Roles::Medecin)->with('compte')->get();

        return response()->json([
            'visites' => $visites,
            'docteurs' => $docteurs,
        ]);
    }

    public function create()
    {
        $docteurs = User::role(Roles::Medecin)->with('compte')->get();
        $priorites = [1, 2, 3, 4, 5];
        $symptomes = array_map(
            fn(Symptomes $symptome) => mb_convert_case($symptome->value, MB_CASE_TITLE, 'UTF-8'),
            Symptomes::cases()
        );

        return response()->json([
            'docteurs' => $docteurs,
            'priorites' => $priorites,
            'symptomes' => $symptomes,
        ]);
    }

    public function store(Request $request, $compte_id)
    {
        try {
            $request->validate([
                'visite_date_heure' => ['required', 'date'],
                'docteur_id' => ['required', 'exists:users,id'],
                'symptomes' => ['required', 'array'],
                'commentaire' => ['required', 'string'],
                'description' => ['required', 'string'],
                'priorite' => ['required', 'integer', 'min:1', 'max:5'],
                'diagnostique_date_heure' => ['required', 'date'],
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        if (!User::find($compte_id)?->hasRole(Roles::Patient->value)) {
            return response()->json([
                'message' => 'L\'utilisateur spécifié n\'est pas un patient',
            ], 400);
        }

        if (!User::find($request->docteur_id)?->hasRole(Roles::Medecin->value)) {
            return response()->json([
                'message' => 'L\'utilisateur spécifié n\'est pas un médecin',
            ], 400);
        }

        foreach ($request->symptomes as $symptome) {
            $normalized = mb_strtolower(trim($symptome));

            if (!Symptomes::tryFrom($normalized)) {
                return response()->json([
                    'message' => "Symptome invalide: {$symptome}",
                ], 400);
            }
        }

        if ($request->visite_date_heure > $request->diagnostique_date_heure) {
            return response()->json([
                'message' => 'La date et l\'heure de la visite doivent être antérieures à celles du diagnostique',
            ], 400);
        }

        try {
            $diagnostic = Diagnostique::create([
                'patient_id' => $request->compte_id,
                'commentaire' => $request->commentaire . ' --- Symptômes : ' . implode(', ', $request->symptomes),
                'description' => $request->description,
                'date_heure' => $request->diagnostique_date_heure,
                'priorite' => $request->priorite,
            ]);

            Visite::create([
                'patient_id' => $request->compte_id,
                'docteur_id' => $request->docteur_id,
                'diagnostique_id' => $diagnostic->id,
                'date_heure' => $request->visite_date_heure,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la création de la visite: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Visite ajoutée avec succès',
        ], 201);
    }

    public function show($compte_id, $id)
    {
        $visite = Visite::where('patient_id', $compte_id)
            ->where('id', $id)
            ->first();

        if (!$visite) {
            return response()->json([
                'message' => 'La visite n\'existe pas',
            ], 404);
        }

        $visite->load('docteur', 'diagnostique');

        return response()->json([
            'visite' => $visite,
        ]);
    }

    public function edit($compte_id, $id)
    {
        $visite = Visite::where('patient_id', $compte_id)
            ->where('id', $id)
            ->first();

        if (!$visite) {
            return response()->json([
                'message' => 'La visite n\'existe pas',
            ], 404);
        }

        $visite->load('docteur', 'diagnostique');
        $docteurs = User::role(Roles::Medecin)->with('compte')->get();
        $priorites = [1, 2, 3, 4, 5];

        return response()->json([
            'visite' => $visite,
            'docteurs' => $docteurs,
            'priorites' => $priorites,
        ]);
    }

    public function update($compte_id, Request $request)
    {
        try {
            $request->validate([
                'visite_id' => ['required', 'exists:visites,id'],
                'visite_date_heure' => ['required', 'date'],
                'docteur_id' => ['required', 'exists:users,id'],
                'commentaire' => ['required', 'string'],
                'description' => ['required', 'string'],
                'priorite' => ['required', 'integer', 'min:1', 'max:5'],
                'diagnostique_date_heure' => ['required', 'date'],
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        $visite = Visite::where('patient_id', $compte_id)
            ->where('id', $request->visite_id)
            ->first();

        if (!$visite) {
            return response()->json([
                'message' => 'La visite n\'existe pas',
            ], 404);
        }

        $visite->load('diagnostique');

        if (!User::find($request->docteur_id)?->hasRole(Roles::Medecin->value)) {
            return response()->json([
                'message' => 'L\'utilisateur spécifié n\'est pas un médecin',
            ], 400);
        }

        if ($request->visite_date_heure > $request->diagnostique_date_heure) {
            return response()->json([
                'message' => 'La date et l\'heure de la visite doivent être antérieures à celles du diagnostique',
            ], 400);
        }

        try {
            $visite->update([
                'date_heure' => $request->visite_date_heure,
                'docteur_id' => $request->docteur_id,
            ]);

            $visite->diagnostique->update([
                'commentaire' => $request->commentaire,
                'description' => $request->description,
                'date_heure' => $request->diagnostique_date_heure,
                'priorite' => $request->priorite,
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'La visite n\'a pas pu être modifié : ' . $e->getMessage()], 400);
        }

        return response()->json([["message" => "La visite a été modfié."], 200]);
    }

    public function destroy($compte_id, $id)
    {
        $visite = Visite::where('patient_id', $compte_id)
            ->where('id', $id)
            ->first();

        if (!$visite) {
            return response()->json([
                'message' => 'La visite n\'existe pas',
            ], 404);
        }

        try {
            $visite->diagnostique?->delete();
            $visite->delete();
        } catch (Exception $e) {
            return response()->json(['message' => 'Cette visite n\'a pas pu être supprimée : ' . $e->getMessage()], 400);
        }

        return response()->json([["message" => "La visite a été supprimée."], 200]);
    }

}
