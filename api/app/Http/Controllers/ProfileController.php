<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Routing\Controller;

class ProfileController extends Controller
{
    public function show($id)
    {
        $user = User::with(['compte'])->findOrFail($id);
        $user->load(['compte.adresses', 'compte.telephones']);

        return response()->json([
            'user' => $user,
            'roles' => $user->compte->user->roles->pluck('name')->toArray(),
        ]);
    }
}
