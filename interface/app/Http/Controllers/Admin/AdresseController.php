<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

/**
 * Contrôleur des adresses
 * Gère les opérations CRUD sur les adresses associées aux comptes
 */
class AdresseController extends Controller
{
    /**
     * Affiche la liste des adresses d'un compte avec filtrage
     *
     * @param Request $request Les paramètres de filtrage (province, type)
     * @param int $compte_id L'identifiant du compte
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function index(Request $request, int $compte_id)
    {
        $query = array_filter([
            'province' => $request->province,
            'type' => $request->type,
        ]);

        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/$compte_id/adresses", $query);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $adresses = $response->json()['adresses'];
        $provinces = $response->json()['provinces'];
        $types = $response->json()['types'];

        return view('admin.adresse.index', compact('adresses', 'provinces', 'types', 'compte_id'));
    }

    /**
     * Affiche le formulaire de création d'une nouvelle adresse
     *
     * @param int $compte_id L'identifiant du compte
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function create(int $compte_id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/$compte_id/adresses/create");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $provinces = $response->json()['provinces'];
        $types = $response->json()['types'];

        return view('admin.adresse.create', compact('compte_id', 'provinces', 'types'));
    }

    /**
     * Enregistre une nouvelle adresse pour un compte
     *
     * @param Request $request Les données de l'adresse
     * @param int $compte_id L'identifiant du compte
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function store(Request $request, int $compte_id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->post("http://host.docker.internal:81/api/admins/comptes/$compte_id/adresses/store", [
            'numero' => $request->numero,
            'rue' => $request->rue,
            'ville' => $request->ville,
            'province' => $request->province,
            'code_postal' => $request->code_postal,
            'type' => $request->type,
        ]);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('adresses.index', compact('compte_id'))->with('success', 'Adresse créée avec succès');
    }

    /**
     * Affiche les détails d'une adresse spécifique
     *
     * @param int $compte_id L'identifiant du compte
     * @param int $id L'identifiant de l'adresse
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function show(int $compte_id, int $id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/$compte_id/adresses/$id");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $adresse = $response->json()['adresse'];

        return view('admin.adresse.show', compact('adresse', 'compte_id'));
    }

    /**
     * Affiche le formulaire de modification d'une adresse
     *
     * @param int $compte_id L'identifiant du compte
     * @param int $id L'identifiant de l'adresse
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function edit(int $compte_id, int $id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/$compte_id/adresses/$id/edit");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $adresse = $response->json()['adresse'];
        $provinces = $response->json()['provinces'];
        $types = $response->json()['types'];

        return view('admin.adresse.edit', compact('compte_id', 'adresse', 'provinces', 'types'));
    }

    /**
     * Met à jour les informations d'une adresse existante
     *
     * @param Request $request Les données mises à jour
     * @param int $compte_id L'identifiant du compte
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function update(Request $request, int $compte_id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->put("http://host.docker.internal:81/api/admins/comptes/$compte_id/adresses/update", [
            'adresse_id' => $request->adresse_id,
            'numero' => $request->numero,
            'rue' => $request->rue,
            'ville' => $request->ville,
            'province' => $request->province,
            'code_postal' => $request->code_postal,
            'type' => $request->type,
        ]);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('adresses.index', compact('compte_id'))->with('success', 'Adresse mise à jour avec succès');
    }

    /**
     * Supprime une adresse
     *
     * @param int $compte_id L'identifiant du compte
     * @param int $id L'identifiant de l'adresse
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function destroy(int $compte_id, int $id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->delete("http://host.docker.internal:81/api/admins/comptes/$compte_id/adresses/$id");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('adresses.index', $compte_id)
            ->with('success', 'Adresse supprimée avec succès.');
    }
}
