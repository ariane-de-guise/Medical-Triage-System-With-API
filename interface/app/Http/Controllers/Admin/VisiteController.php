<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class VisiteController extends Controller
{
    /**
     * @throws ConnectionException
     */
    public function index(Request $request, $compte_id) {
        $query = array_filter([
            'docteur_id' => $request->docteur_id,
            'date_heure' => $request->date_heure,
        ]);

        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/{$compte_id}/visites", $query);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $visites = $response->json()['visites'];
        $docteurs = $response->json()['docteurs'];

        return view('admin.visite.index', compact('visites', 'docteurs', 'compte_id'));
    }

    /**
     * @throws ConnectionException
     */
    public function create($compte_id) {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/{$compte_id}/visites/create");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $priorites = $response->json()['priorites'];
        $docteurs = $response->json()['docteurs'];
        $symptomes = $response->json()['symptomes'];

        return view('admin.visite.create', compact('compte_id', 'priorites', 'docteurs', 'symptomes'));
    }

    /**
     * @throws ConnectionException
     */
    public function store(Request $request, $compte_id) {
        $response = Http::withToken(session('token'))->acceptJson()->post("http://host.docker.internal:81/api/admins/comptes/{$compte_id}/visites/store", [
            'docteur_id' => $request->docteur_id,
            'visite_date_heure' => $request->visite_date_heure,
            'symptomes' => $request->symptomes,
            'commentaire' => $request->commentaire,
            'diagnostique_date_heure' => $request->diagnostique_date_heure,
            'description' => $request->description,
            'priorite' => $request->priorite,
        ]);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('visites.index', compact('compte_id'))->with('success', 'Visite créée avec succès');
    }

    /**
     * @throws ConnectionException
     */
    public function show($compte_id, $id) {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/{$compte_id}/visites/{$id}");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $visite = $response->json()['visite'];

        return view('admin.visite.show', compact('visite', 'compte_id'));
    }

    /**
     * @throws ConnectionException
     */
    public function edit($compte_id, $id) {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/{$compte_id}/visites/{$id}/edit");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $priorites = $response->json()['priorites'];
        $docteurs = $response->json()['docteurs'];
        $visite = $response->json()['visite'];

        return view('admin.visite.edit', compact('compte_id', 'priorites', 'docteurs', 'visite'));
    }

    /**
     * @throws ConnectionException
     */
    public function update(Request $request, $compte_id) {
        $response = Http::withToken(session('token'))->acceptJson()->put("http://host.docker.internal:81/api/admins/comptes/{$compte_id}/visites/update", [
            'docteur_id' => $request->docteur_id,
            'visite_date_heure' => $request->visite_date_heure,
            'visite_id' => $request->visite_id,
            'commentaire' => $request->commentaire,
            'diagnostique_date_heure' => $request->diagnostique_date_heure,
            'description' => $request->description,
            'priorite' => $request->priorite,
        ]);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('visites.index', compact('compte_id'))->with('success', 'Visite mise à jour avec succès');
    }

    /**
     * @throws ConnectionException
     */
    public function destroy($compte_id, $id) {
        $response = Http::withToken(session('token'))->acceptJson()->delete("http://host.docker.internal:81/api/admins/comptes/{$compte_id}/visites/{$id}");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('visites.index', $compte_id)
            ->with('success', 'Visite supprimée avec succès.');
    }
}
