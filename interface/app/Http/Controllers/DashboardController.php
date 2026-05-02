<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

/**
 * Contrôleur du tableau de bord
 * Gère l'affichage du tableau de bord principal avec les informations utilisateur
 */
class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord avec les informations du profil et les rôles de l'utilisateur
     *
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function index()
    {
        $userId = session('user')['id'] ?? null;

        if (!$userId) {
            return redirect('/login');
        }

        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/profiles/$userId");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $user = $response['user'];
        $roles = $response['roles'];
        return view('dashboard', compact('user', 'roles'));
    }
}
