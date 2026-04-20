<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class PatientController extends Controller
{
    public function index()
    {
        $userId = session('user')['id'] ?? null;

        $response = Http::get("http://host.docker.internal:81/api/patient/$userId");
        $user = $response['user'];
        $position = $response['position'];

        return view('patient', compact('user', 'position'));
    }

    public function ajoutFile() {
        $userId = session('user')['id'] ?? null;

        $response = Http::post("http://host.docker.internal:81/api/patient/$userId/add");

        if ($response->failed()) {
            return back()->withErrors(['message' => 'Failed to enqueue']);
        }

        return redirect()->route('patient')->with('success', 'Vous avez été ajouté à la file d\'attente');
    }

    public function retireFile() {
        $userId = session('user')['id'] ?? null;

        $response = Http::delete("http://host.docker.internal:81/api/patient/$userId/remove");

        if ($response->failed()) {
            return back()->withErrors(['message' => 'Failed to dequeue']);
        }

        return redirect()->route('patient')->with('success', 'Vous avez retiré de la file d\'attente');
    }
}
