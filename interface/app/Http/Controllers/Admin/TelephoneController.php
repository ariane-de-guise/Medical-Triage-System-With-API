<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

/**
 * Contrôleur des téléphones
 * Gère les opérations CRUD sur les numéros de téléphone associés aux comptes
 */
class TelephoneController extends Controller
{
    /**
     * Affiche la liste des téléphones d'un compte avec filtrage
     *
     * @param Request $request Les paramètres de filtrage (date création, contenu)
     * @param int $compte_id L'identifiant du compte
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function index(Request $request, int $compte_id)
    {
        $query = array_filter([
            'created_at' => $request->created_at,
            'contains' => $request->contains,
        ]);

        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/$compte_id/telephones", $query);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $telephones = $response->json()['telephones'];

        return view('admin.telephone.index', compact('telephones', 'compte_id'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau téléphone
     *
     * @param int $compte_id L'identifiant du compte
     * @return View
     */
    public function create(int $compte_id)
    {
        return view('admin.telephone.create', compact('compte_id'));
    }

    /**
     * Enregistre un nouveau numéro de téléphone pour un compte
     *
     * @param Request $request Les données du téléphone
     * @param int $compte_id L'identifiant du compte
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function store(Request $request, int $compte_id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->post("http://host.docker.internal:81/api/admins/comptes/$compte_id/telephones/store", [
            'telephone' => $request->telephone,
        ]);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('telephones.index', compact('compte_id'))->with('success', 'Téléphone créée avec succès');
    }

    /**
     * Affiche les détails d'un téléphone spécifique
     *
     * @param int $compte_id L'identifiant du compte
     * @param int $id L'identifiant du téléphone
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function show(int $compte_id, int $id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/$compte_id/telephones/$id");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $telephone = $response->json()['telephone'];

        return view('admin.telephone.show', compact('telephone', 'compte_id'));
    }

    /**
     * Affiche le formulaire de modification d'un téléphone
     *
     * @param int $compte_id L'identifiant du compte
     * @param int $id L'identifiant du téléphone
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function edit(int $compte_id, int $id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/$compte_id/telephones/$id/edit");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $telephone = $response->json()['telephone'];

        return view('admin.telephone.edit', compact('compte_id', 'telephone'));
    }

    /**
     * Met à jour les informations d'un numéro de téléphone existant
     *
     * @param Request $request Les données mises à jour
     * @param int $compte_id L'identifiant du compte
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function update(Request $request, int $compte_id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->put("http://host.docker.internal:81/api/admins/comptes/$compte_id/telephones/update", [
            'telephone_id' => $request->telephone_id,
            'telephone' => $request->telephone,
        ]);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('telephones.index', compact('compte_id'))->with('success', 'Téléphone mise à jour avec succès');
    }

    /**
     * Supprime un numéro de téléphone
     *
     * @param int $compte_id L'identifiant du compte
     * @param int $id L'identifiant du téléphone
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function destroy(int $compte_id, int $id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->delete("http://host.docker.internal:81/api/admins/comptes/$compte_id/telephones/$id");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('telephones.index', $compte_id)
            ->with('success', 'Téléphone supprimée avec succès.');
    }
}
