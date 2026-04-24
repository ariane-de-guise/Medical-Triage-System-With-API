<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class TriageController extends Controller
{
    public function index(Request $request) {
        $query = array_filter([
            'file_attente' => $request->file_attente,
            'priorite' => $request->priorite,
        ]);

        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/triages", $query);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $triages = $response->json();
        return view('admin.triage.index', compact('triages'));
    }

    public function create() {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/triages/create");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $comptes = $response['comptes'];
        $files = $response['files'];

        return view('admin.triage.create', compact('comptes', 'files'));
    }

    public function store(Request $request) {
        $response = Http::withToken(session('token'))->acceptJson()->post("http://host.docker.internal:81/api/admins/triages/store", [
            'compte_id' => $request->compte_id,
            'file' => $request->file,
            'priorite' => $request->priorite,
        ]);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('triages.index')
            ->with('success', 'Compte ajouté au triage.');
    }

    public function show($id) {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/triages/{$id}");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $triage = $response->json();
        return view('admin.triage.show', compact('triage'));
    }

    public function edit($id) {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/triages/{$id}/edit");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $triage = $response['triage'];
        $files = $response['files'];

        return view('admin.triage.edit', compact('triage', 'files'));
    }

    /**
     * @throws ConnectionException
     */
    public function update(Request $request) {
        $response = Http::withToken(session('token'))->acceptJson()->put("http://host.docker.internal:81/api/admins/triages/update", [
            'triage_id' => $request->triage_id,
            'file' => $request->file,
            'priorite' => $request->priorite,
        ]);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('triages.index')
            ->with('success', 'Élément de triage mis à jour.');
    }

    /**
     * @throws ConnectionException
     */
    public function destroy($id) {
        $response = Http::withToken(session('token'))->acceptJson()->delete("http://host.docker.internal:81/api/admins/triages/{$id}");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('triages.index')
            ->with('success', 'Compte retiré de la file avec succès.');
    }
}
