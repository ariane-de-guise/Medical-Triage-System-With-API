<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class InfirmierController extends Controller
{
    /**
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
     * @throws ConnectionException
     */
    public function show(Request $request) {
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
     * @throws ConnectionException
     */
    public function destroy() {
        $userId = session('user')['id'] ?? null;
        $response = Http::withToken(session('token'))->acceptJson()->delete("http://host.docker.internal:81/api/infirmiers/$userId");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('infirmier')->with('success', 'Vous vous êtes déconnecté avec succès');
    }

    /**
     * @throws ConnectionException
     */
    public function store() {
        $userId = session('user')['id'] ?? null;
        $response = Http::withToken(session('token'))->acceptJson()->post("http://host.docker.internal:81/api/infirmiers/$userId");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('infirmier')->with('success', 'Vous vous êtes connecté avec succès');
    }

    /**
     * @throws ConnectionException
     */
    public function update(Request $request) {
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
