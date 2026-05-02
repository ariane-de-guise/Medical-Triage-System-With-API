<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

/**
 * Contrôleur des visites médicales
 * Gère les opérations CRUD sur les visites et consultations médicales des patients
 */
class VisiteController extends Controller
{
    /**
     * Affiche la liste des visites d'un patient avec filtrage par médecin et date
     *
     * @param Request $request Les paramètres de filtrage
     * @param int $compte_id L'identifiant du patient
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function index(Request $request, int $compte_id)
    {
        $query = array_filter([
            'docteur_id' => $request->docteur_id,
            'date_heure' => $request->date_heure,
        ]);

        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/$compte_id/visites", $query);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $visites = $response->json()['visites'];
        $docteurs = $response->json()['docteurs'];

        return view('admin.visite.index', compact('visites', 'docteurs', 'compte_id'));
    }

    /**
     * Affiche le formulaire de création d'une nouvelle visite médicale
     *
     * @param int $compte_id L'identifiant du patient
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function create(int $compte_id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/$compte_id/visites/create");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $priorites = $response->json()['priorites'];
        $docteurs = $response->json()['docteurs'];
        $symptomes = $response->json()['symptomes'];

        return view('admin.visite.create', compact('compte_id', 'priorites', 'docteurs', 'symptomes'));
    }

    /**
     * Enregistre une nouvelle visite médicale pour un patient
     *
     * @param Request $request Les données de la visite
     * @param int $compte_id L'identifiant du patient
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function store(Request $request, int $compte_id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->post("http://host.docker.internal:81/api/admins/comptes/$compte_id/visites/store", [
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
     * Affiche les détails d'une visite médicale spécifique
     *
     * @param int $compte_id L'identifiant du patient
     * @param int $id L'identifiant de la visite
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function show(int $compte_id, int $id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/$compte_id/visites/$id");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $visite = $response->json()['visite'];

        return view('admin.visite.show', compact('visite', 'compte_id'));
    }

    /**
     * Affiche le formulaire de modification d'une visite médicale
     *
     * @param int $compte_id L'identifiant du patient
     * @param int $id L'identifiant de la visite
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function edit(int $compte_id, int $id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/$compte_id/visites/$id/edit");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $priorites = $response->json()['priorites'];
        $docteurs = $response->json()['docteurs'];
        $visite = $response->json()['visite'];

        return view('admin.visite.edit', compact('compte_id', 'priorites', 'docteurs', 'visite'));
    }

    /**
     * Met à jour une visite médicale existante
     *
     * @param Request $request Les données mises à jour
     * @param int $compte_id L'identifiant du patient
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function update(Request $request, int $compte_id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->put("http://host.docker.internal:81/api/admins/comptes/$compte_id/visites/update", [
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
     * Supprime une visite médicale
     *
     * @param int $compte_id L'identifiant du patient
     * @param int $id L'identifiant de la visite
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function destroy(int $compte_id, int $id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->delete("http://host.docker.internal:81/api/admins/comptes/$compte_id/visites/$id");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('visites.index', $compte_id)
            ->with('success', 'Visite supprimée avec succès.');
    }
}
