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
     * @throws ConnectionException
     */
    public function show() {
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
     * @throws ConnectionException
     */
    public function destroy() {
        $userId = session('user')['id'] ?? null;
        $response = Http::withToken(session('token'))->acceptJson()->delete("http://host.docker.internal:81/api/medecins/$userId");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('medecin')->with('success', 'Vous vous êtes déconnecté avec succès');
    }

    /**
     * @throws ConnectionException
     */
    public function store() {
        $userId = session('user')['id'] ?? null;
        $response = Http::withToken(session('token'))->acceptJson()->post("http://host.docker.internal:81/api/medecins/$userId");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('medecin')->with('success', 'Vous vous êtes connecté avec succès');
    }

    /**
     * @throws ConnectionException
     */
    public function update(Request $request) {
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
