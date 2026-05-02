<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

/**
 * Contrôleur du triage
 * Gère le triage des patients en fonction de leur priorité et de leur file d'attente
 */
class TriageController extends Controller
{
    /**
     * Affiche la liste du triage avec filtrage par file d'attente et priorité
     *
     * @param Request $request Les paramètres de filtrage
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function index(Request $request)
    {
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

    /**
     * Affiche le formulaire de création d'un nouvel élément de triage
     *
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function create()
    {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/triages/create");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $comptes = $response['comptes'];
        $files = $response['files'];

        return view('admin.triage.create', compact('comptes', 'files'));
    }

    /**
     * Enregistre un nouveau triage pour un patient
     *
     * @param Request $request Les données du triage
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function store(Request $request)
    {
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

    /**
     * Affiche les détails d'un élément de triage spécifique
     *
     * @param int $id L'identifiant du triage
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function show(int $id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/triages/$id");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $triage = $response->json();
        return view('admin.triage.show', compact('triage'));
    }

    /**
     * Affiche le formulaire de modification d'un élément de triage
     *
     * @param int $id L'identifiant du triage
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function edit(int $id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/triages/$id/edit");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $triage = $response['triage'];
        $files = $response['files'];

        return view('admin.triage.edit', compact('triage', 'files'));
    }

    /**
     * Met à jour un élément de triage existant
     *
     * @param Request $request Les données mises à jour
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function update(Request $request)
    {
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
     * Supprime un élément de triage
     *
     * @param int $id L'identifiant du triage
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function destroy(int $id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->delete("http://host.docker.internal:81/api/admins/triages/$id");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('triages.index')
            ->with('success', 'Compte retiré de la file avec succès.');
    }
}
