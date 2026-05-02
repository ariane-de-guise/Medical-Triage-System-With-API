<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

/**
 * Contrôleur des comptes
 * Gère les opérations CRUD sur les comptes utilisateurs
 */
class CompteController extends Controller
{
    /**
     * Affiche la liste paginée des comptes avec filtrage par année de naissance et nom
     *
     * @param Request $request Les paramètres de filtrage
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function index(Request $request)
    {
        $query = array_filter([
            'annee_naissance' => $request->annee_naissance,
            'name_contains' => $request->name_contains,
            'page' => $request->page,
        ]);

        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes", $query);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $comptes = $response->json()['comptes'];
        $current_page = $response->json()['current_page'];
        $max_page = $response->json()['max_page'];

        return view('admin.compte.index', compact('comptes', 'current_page', 'max_page'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau compte
     *
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function create()
    {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/create");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $roles = $response->json()['roles'];
        return view('admin.compte.create', compact('roles'));
    }

    /**
     * Enregistre un nouveau compte utilisateur
     *
     * @param Request $request Les données du compte
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function store(Request $request)
    {
        $response = Http::withToken(session('token'))->acceptJson()->post("http://host.docker.internal:81/api/admins/comptes/store", [
            'email' => $request->email,
            'password' => $request->password,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'date_naissance' => $request->date_naissance,
            'role' => $request->role,
        ]);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('comptes.index')
            ->with('success', 'Compte créé.');
    }

    /**
     * Affiche les détails d'un compte spécifique
     *
     * @param int $id L'identifiant du compte
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function show(int $id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/$id");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $compte = $response->json()['compte'];
        $roles = $response->json()['roles'];
        return view('admin.compte.show', compact('compte', 'roles'));
    }

    /**
     * Affiche le formulaire de modification d'un compte
     *
     * @param int $id L'identifiant du compte
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function edit(int $id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/$id/edit");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $roles = $response['roles'];
        $compte = $response['compte'];

        return view('admin.compte.edit', compact('roles', 'compte'));
    }

    /**
     * Met à jour un compte existant (rôles et informations personnelles)
     *
     * @param Request $request Les données mises à jour
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function update(Request $request)
    {
        $response = Http::withToken(session('token'))->acceptJson()->put("http://host.docker.internal:81/api/admins/comptes/update", [
            'compte_id' => $request->compte_id,
            'add_role' => $request->add_role,
            'remove_role' => $request->remove_role,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'date_naissance' => $request->date_naissance,
        ]);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('comptes.index')
            ->with('success', 'Compte mis à jour.');
    }

    /**
     * Supprime un compte utilisateur
     *
     * @param int $id L'identifiant du compte
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function destroy(int $id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->delete("http://host.docker.internal:81/api/admins/comptes/$id");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('comptes.index')
            ->with('success', 'Compte supprimé avec succès.');
    }
}
