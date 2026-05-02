<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

/**
 * Contrôleur de profil
 * Gère l'affichage du profil utilisateur
 */
class ProfileController extends Controller
{
    /**
     * Affiche le profil détaillé de l'utilisateur connecté
     *
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function show()
    {
        $userId = session('user')['id'] ?? null;
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/profiles/$userId");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $user = $response['user'];
        return view('profile.show', compact('user'));
    }
}
