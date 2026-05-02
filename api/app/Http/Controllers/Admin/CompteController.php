<?php

namespace App\Http\Controllers\Admin;

use App\Algorithms\Roles;
use App\Algorithms\TimeCard;
use App\Models\Compte;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CompteController extends Controller
{
    public function index(Request $request) {
        $query = Compte::query();

        if ($request->filled('annee_naissance')) {
            $query->where('date_naissance', '>=', $request->annee_naissance . '-01-01');
        }

        if ($request->filled('name_contains')) {
            $name = $request->name_contains;

            $query->where(function ($q) use ($name) {
                $q->where('prenom', 'like', "%$name%")
                    ->orWhere('nom', 'like', "%$name%");
            });
        }

        $paginator = $query->paginate(10);

        return response()->json([
            'comptes' => $paginator->items(),
            'current_page' => $paginator->currentPage(),
            'max_page' => $paginator->lastPage(),
        ]);
    }

    public function create() {
        $roles = [Roles::Infirmier->value, Roles::Medecin->value, Roles::Admin->value, Roles::Patient->value];

        return response()->json([
            'roles' => $roles
        ]);
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'email' => ['required', 'email', 'unique:users'],
                'password' => ['required', 'min:6'],
                'nom' => ['required', 'string', 'max:255'],
                'prenom' => ['required', 'string', 'max:255'],
                'date_naissance' => ['required', 'date', 'before:today', 'date_format:Y-m-d', 'after:1900-01-01'],
                'role' => ['required', 'exists:roles,name'],
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        try {
            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $user->assignRole($request->role);

            Compte::create([
                'user_id' => $user->id,
                'date_naissance' => $request->date_naissance,
                'prenom' => $request->prenom,
                'nom' => $request->nom,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la création du compte: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Compte ajouté avec succès',
        ], 201);
    }

    public function show($id)
    {
        $query = Compte::find($id);

        if (!$query) {
            return response()->json([
                'message' => 'Le compte n\'existe pas',
            ], 404);
        }

        $compte = $query->load('user');
        $roles = $compte->user->roles->pluck('name')->toArray();

        return response()->json([
            'compte' => $compte,
            'roles' => $roles,
        ]);
    }

    public function edit($id) {
        $query = Compte::find($id);

        if (!$query) {
            return response()->json([
                'message' => 'Le compte n\'existe pas',
            ], 404);
        }

        $compte = $query->load('user');
        $roles = [Roles::Infirmier->value, Roles::Medecin->value, Roles::Admin->value, Roles::Patient->value];

        return response()->json([
            'compte' => $compte,
            'roles' => $roles,
        ]);
    }

    public function update(Request $request) {
        try {
            $request->validate([
                'compte_id' => 'required|exists:comptes,id',
                'add_role' => 'nullable|exists:roles,name',
                'remove_role' => 'nullable|exists:roles,name',
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'date_naissance' => 'required|date|before:today|date_format:Y-m-d|after:1900-01-01',
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        $query = Compte::find($request->compte_id);

        if (!$query) {
            return response()->json(["message" => "Le compte n'existe pas"], 404);
        }

        $compte = $query->load('user.roles');

        if ($request->add_role != null) {
            if ($compte->user->roles->contains('name', $request->add_role)) {
                return response()->json(['message' => 'Ce compte a déjà ce rôle'], 403);
            }

            try {
                $compte->user->assignRole($request->add_role);
            }
            catch (Exception $e) {
                return response()->json(['message' => 'Ce rôle n\' a pas pu être ajouté : ' . $e->getMessage()], 400);
            }
        }

        if ($request->remove_role != null) {
            if (!$compte->user->roles->contains('name', $request->remove_role)) {
                return response()->json(['message' => 'Ce compte n\'a pas ce rôle'], 403);
            }

            try {
                $compte->user->removeRole($request->remove_role);
            }
            catch (Exception $e) {
                return response()->json(['message' => 'Ce rôle n\' a pas pu être supprimé : ' . $e->getMessage()], 400);
            }
        }

        try {
            $compte->update([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'date_naissance' => $request->date_naissance,
            ]);
        }
        catch (Exception $e) {
            return response()->json(['message' => 'Ce compte n\'a pas pu être modifié : ' . $e->getMessage()], 400);
        }

        return response()->json([["message" => "L'élément de triage a été modfié."], 200]);
    }

    public function destroy($id) {
        $query = Compte::find($id);

        if (!$query) {
            return response()->json(["message" => "Le compte n'existe pas"], 404);
        }

        if ($query->personnel != null) {
            TimeCard::logout($query->user, $query->personnel->role);
        }

        try {
            $query->delete();
        }
        catch (Exception $e) {
            return response()->json(['message' => 'Ce compte n\'a pas pu être supprimé : ' . $e->getMessage()], 400);
        }

        return response()->json([["message" => "Le compte a été supprimé."], 200]);
    }
}
