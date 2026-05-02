<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

/**
 * Contrôleur du patient
 * Gère l'inscription et la gestion de la file d'attente du patient
 */
class PatientController extends Controller
{
    /**
     * Affiche la page d'accueil du patient avec sa position dans la file d'attente
     *
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function index()
    {
        $userId = session('user')['id'] ?? null;

        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/patients/$userId");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $user = $response['user'];
        $position = $response['position'];

        return view('patient.index', compact('user', 'position'));
    }

    /**
     * Ajoute le patient à la file d'attente
     *
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function store()
    {
        $userId = session('user')['id'] ?? null;

        $response = Http::withToken(session('token'))->acceptJson()->post("http://host.docker.internal:81/api/patients/$userId");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('patient')->with('success', 'Vous avez été ajouté à la file d\'attente');
    }

    /**
     * Retire le patient de la file d'attente
     *
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function destroy()
    {
        $userId = session('user')['id'] ?? null;

        $response = Http::withToken(session('token'))->acceptJson()->delete("http://host.docker.internal:81/api/patients/$userId");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('patient')->with('success', 'Vous avez retiré de la file d\'attente');
    }
}
