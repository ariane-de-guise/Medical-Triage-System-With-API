<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class PatientController extends Controller
{
    /**
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
     * @throws ConnectionException
     */
    public function store() {
        $userId = session('user')['id'] ?? null;

        $response = Http::withToken(session('token'))->acceptJson()->post("http://host.docker.internal:81/api/patients/$userId");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('patient')->with('success', 'Vous avez été ajouté à la file d\'attente');
    }

    /**
     * @throws ConnectionException
     */
    public function destroy() {
        $userId = session('user')['id'] ?? null;

        $response = Http::withToken(session('token'))->acceptJson()->delete("http://host.docker.internal:81/api/patients/$userId");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('patient')->with('success', 'Vous avez retiré de la file d\'attente');
    }
}
