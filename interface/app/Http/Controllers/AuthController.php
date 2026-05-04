<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

/**
 * Contrôleur d'authentification
 * Gère les opérations d'authentification, d'enregistrement et de déconnexion des utilisateurs
 */
class AuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion
     *
     * @return View
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Affiche le formulaire d'enregistrement avec les provinces et types disponibles
     *
     * @return View
     * @throws ConnectionException
     */
    public function register()
    {
        try {
            $provinces = collect(Http::get('http://host.docker.internal:81/api/provinces')->json())
                ->pluck('name')
                ->toArray();
        } catch (ConnectionException) {
            $provinces = [];
        }

        try {
            $types = collect(Http::get('http://host.docker.internal:81/api/types')->json())
                ->pluck('name')
                ->toArray() ?? [];
        } catch (ConnectionException) {
            $types = [];
        }

        return view('auth.register', compact('provinces', 'types'));
    }

    /**
     * Traite la demande de connexion
     * Valide les identifiants et crée une session utilisateur
     *
     * @param Request $request Les données de connexion (email, password)
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function sendLoginRequest(Request $request)
    {
        $response = Http::post('http://host.docker.internal:81/api/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->failed()) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'api' => $response->json()['message'] ?? 'Une erreur est survenue.'
                ]);
        }

        $user = $response['user'] ?? null;

        session([
            'user' => $user,
            'token' => $response->json()['token'],
        ]);

        return redirect()->route('dashboard');
    }

    /**
     * Déconnecte l'utilisateur et détruit la session
     *
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function logout()
    {
        $token = session('token');

        $response = Http::withToken($token)->acceptJson()->post('http://host.docker.internal:81/api/logout');

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        session()->forget(['user', 'token']);

        return redirect('login');
    }

    /**
     * Traite la demande d'enregistrement d'un nouvel utilisateur
     *
     * @param Request $request Les données d'enregistrement (email, password, informations personnelles, etc.)
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function sendRegisterRequest(Request $request)
    {
        $response = Http::post('http://host.docker.internal:81/api/register', [
            'email' => $request->email,
            'password' => $request->password,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'date_naissance' => $request->date_naissance,
            'numero' => $request->numero,
            'rue' => $request->rue,
            'ville' => $request->ville,
            'code_postal' => $request->code_postal,
            'province' => $request->province,
            'type' => $request->type,
            'telephone' => $request->telephone,
        ]);

        if ($response->failed()) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'api' => $response->json()['message'] ?? 'Une erreur est survenue.'
                ]);
        }

        return redirect()->route('login')
            ->with('success', 'Compte créé, vous pouvez maintenant vous connecter.');
    }
}
