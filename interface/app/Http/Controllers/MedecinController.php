<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class MedecinController extends Controller
{
    /**
     * @throws ConnectionException
     */
    public function index()
    {
        $userId = session('user')['id'] ?? null;

        $response = Http::get("http://host.docker.internal:81/api/medecin/$userId");
        $loggedIn = $response['loggedIn'];
        $count = $response['count'];
        $patients = $response['patients'];

        return view('medecin', compact('loggedIn', 'count', 'patients'));
    }

    /**
     * @throws ConnectionException
     */
    public function show() {
        $userId = session('user')['id'] ?? null;
        $response = Http::get("http://host.docker.internal:81/api/medecin/$userId/consultation");

        $patient = $response['patient'];
        return view('consultation', compact('patient'));
    }

    /**
     * @throws ConnectionException
     */
    public function destroy() {
        $userId = session('user')['id'] ?? null;
        $response = Http::delete("http://host.docker.internal:81/api/medecin/$userId");

        if ($response->failed()) {
            return back()->withErrors(['message' => 'Une erreur est survenue lors de la déconnexion.']);
        }

        return redirect()->route('medecin')->with('success', 'Vous vous êtes déconnecté avec succès');
    }

    /**
     * @throws ConnectionException
     */
    public function store() {
        $userId = session('user')['id'] ?? null;
        $response = Http::post("http://host.docker.internal:81/api/medecin/$userId");

        if ($response->failed()) {
            return back()->withErrors(['message' => 'Une erreur est survenue lors de la connexion.']);
        }

        return redirect()->route('medecin')->with('success', 'Vous vous êtes connecté avec succès');
    }

    /**
     * @throws ConnectionException
     */
    public function update(Request $request) {
        $userId = session('user')['id'] ?? null;
        $response = Http::patch("http://host.docker.internal:81/api/medecin/$userId", [
            'diagnostique' => $request->diagnostique,
            'date_heure' => now(),
        ]);

        if ($response->failed()) {
            dd($response->body());
            return back()->withErrors(['message' => 'Une erreur est survenue lors de la sauvegarde du dossier.']);
        }

        return redirect()->route('medecin')->with('success', 'Vous avez terminé la consultation avec succès.');
    }
}
