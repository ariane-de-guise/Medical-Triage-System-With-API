<?php

namespace App\Http\Controllers;

use App\Algorithms\Constants;
use App\Algorithms\Roles;
use App\Models\Adresse;
use App\Models\Telephone;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6'],
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'date_naissance' => ['required', 'date', 'before:today', 'date_format:Y-m-d', 'after:1900-01-01'],
            'numero' => ['required', 'numeric', 'min:1', 'max:1000000000'],
            'rue' => ['required', 'string', 'max:255', 'min:1'],
            'ville' => ['required', 'string', 'max:255', 'min:1'],
            'province' => ['required', 'string', 'size:2', Rule::in(Constants::PROVINCES)],
            'code_postal' => ['required', 'string', 'size:6', 'regex:/^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$/'],
            'type' => ['required', 'string', Rule::in(Constants::TYPES)],
            'telephone' => ['required', 'numeric', 'min:1', 'max:10000000000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole(Roles::Patient);

        $compte = $user->compte()->create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'date_naissance' => $request->date_naissance
        ]);

        $adresse = Adresse::create([
            'numero' => $request->numero,
            'rue' => $request->rue,
            'ville' => $request->ville,
            'province' => $request->province,
            'code_postal' => $request->code_postal,
            'type' => $request->type,
        ]);

        $compte->adresses()->attach($adresse->id);

        $telephone = Telephone::create([
            'numero' => $request->telephone,
        ]);

        $compte->telephones()->attach($telephone->id);

        return response()->json([
            'message' => 'User created'
        ], 201);
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Identifiant ou mot de passe incorrect'
            ], 401);
        }

        $roles = $user->roles->pluck('name')->toArray();
        $token = $user->createToken('user-token', $roles)->plainTextToken;
        $user = User::with(['compte', 'roles'])->findOrFail($user->id);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'compte' => $user->compte,
                'roles' => $user->roles->pluck('name')->toArray(),
            ],
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out'
        ]);
    }
}
