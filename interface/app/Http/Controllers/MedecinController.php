<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

/**
 * Contrôleur du médecin
 * Gère les consultations et diagnostiques des patients par le médecin
 */
class MedecinController extends Controller
{
    /**
     * Affiche la page d'accueil du médecin avec la liste des patients
     *
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function index()
    {
        $userId = session('user')['id'] ?? null;

        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/medecins/$userId");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $loggedIn = $response['loggedIn'];
        $count = $response['count'];
        $patients = $response['patients'];

        return view('medecin.index', compact('loggedIn', 'count', 'patients'));
    }

    /**
     * Affiche les détails de la consultation d'un patient
     *
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function show()
    {
        $userId = session('user')['id'] ?? null;
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/medecins/$userId/consultation");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $patient = $response['patient'];
        $loggedIn = $response['loggedIn'];
        return view('medecin.consultation', compact('patient', 'loggedIn'));
    }

    /**
     * Déconnecte le médecin de son service
     *
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function destroy()
    {
        $userId = session('user')['id'] ?? null;
        $response = Http::withToken(session('token'))->acceptJson()->delete("http://host.docker.internal:81/api/medecins/$userId");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('medecin')->with('success', 'Vous vous êtes déconnecté avec succès');
    }

    /**
     * Connecte le médecin à son service
     *
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function store()
    {
        $userId = session('user')['id'] ?? null;
        $response = Http::withToken(session('token'))->acceptJson()->post("http://host.docker.internal:81/api/medecins/$userId");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('medecin')->with('success', 'Vous vous êtes connecté avec succès');
    }

    /**
     * Met à jour la consultation d'un patient avec le diagnostique
     *
     * @param Request $request Les informations du diagnostique
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function update(Request $request)
    {
        $userId = session('user')['id'] ?? null;
        $response = Http::withToken(session('token'))->acceptJson()->patch("http://host.docker.internal:81/api/medecins/$userId", [
            'diagnostique' => $request->diagnostique,
            'date_heure' => now(),
        ]);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('medecin')->with('success', 'Vous avez terminé la consultation avec succès.');
    }
}
