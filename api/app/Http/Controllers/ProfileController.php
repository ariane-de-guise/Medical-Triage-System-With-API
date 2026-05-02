<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Retourne les informations de profil d'un utilisateur API.
 */
class ProfileController extends Controller
{
    /**
     * Charge le compte, les coordonnées et les rôles associés à l'utilisateur.
     *
     * @param int|string $id Identifiant utilisateur.
     * @return JsonResponse
     */
    public function show(int|string $id)
    {
        $user = User::with(['compte'])->findOrFail($id);
        $user->load(['compte.adresses', 'compte.telephones']);

        return response()->json([
            'user' => $user,
            'roles' => $user->compte->user->roles->pluck('name')->toArray(),
        ]);
    }
}
