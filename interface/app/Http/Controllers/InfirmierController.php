<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

/**
 * Contrôleur de l'infirmier
 * Gère les opérations de consultation de patients et de triage par l'infirmier
 */
class InfirmierController extends Controller
{
    /**
     * Affiche la page d'accueil de l'infirmier avec la liste des patients
     *
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function index()
    {
        $userId = session('user')['id'] ?? null;

        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/infirmiers/$userId");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $loggedIn = $response['loggedIn'];
        $count = $response['count'];
        $patients = $response['patients'];

        return view('infirmier.index', compact('loggedIn', 'count', 'patients'));
    }

    /**
     * Affiche la consultation d'un patient avec ses symptômes et sa priorité
     *
     * @param Request $request Les symptômes du patient
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function show(Request $request)
    {
        $userId = session('user')['id'] ?? null;
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/infirmiers/$userId/consultation", [
            'symptomes' => $request->symptomes,
        ]);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $patient = $response['patient'];
        $priorite = $response['priorite'];
        $symptomes = $response['symptomes'];
        $loggedIn = $response['loggedIn'];

        return view('infirmier.consultation', compact('patient', 'priorite', 'symptomes', 'loggedIn'));
    }

    /**
     * Déconnecte l'infirmier de son service
     *
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function destroy()
    {
        $userId = session('user')['id'] ?? null;
        $response = Http::withToken(session('token'))->acceptJson()->delete("http://host.docker.internal:81/api/infirmiers/$userId");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('infirmier')->with('success', 'Vous vous êtes déconnecté avec succès');
    }

    /**
     * Connecte l'infirmier à son service
     *
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function store()
    {
        $userId = session('user')['id'] ?? null;
        $response = Http::withToken(session('token'))->acceptJson()->post("http://host.docker.internal:81/api/infirmiers/$userId");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('infirmier')->with('success', 'Vous vous êtes connecté avec succès');
    }

    /**
     * Met à jour la consultation d'un patient (priorité, commentaire, symptômes)
     *
     * @param Request $request Les informations mises à jour
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function update(Request $request)
    {
        $userId = session('user')['id'] ?? null;
        $response = Http::withToken(session('token'))->acceptJson()->patch("http://host.docker.internal:81/api/infirmiers/$userId", [
            'commentaire' => $request->commentaire,
            'priorite' => $request->priorite,
            'symptomes' => $request->symptomes,
        ]);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('infirmier')->with('success', 'Vous avez terminé la consultation avec succès.');
    }
}
