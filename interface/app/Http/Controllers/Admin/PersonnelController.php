<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

/**
 * Contrôleur du personnel
 * Gère la gestion du personnel (infirmiers, médecins, etc.) et leur connexion/déconnexion
 */
class PersonnelController extends Controller
{
    /**
     * Affiche la liste du personnel avec filtrage par temps de connexion et rôle
     *
     * @param Request $request Les paramètres de filtrage
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function index(Request $request)
    {
        $query = array_filter([
            'temps_connexion' => $request->temps_connexion,
            'role' => $request->role,
        ]);

        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/personnels", $query);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $personnels = $response->json();
        return view('admin.personnel.index', compact('personnels'));
    }

    /**
     * Affiche le formulaire de création d'un nouvel employé à partir d'un compte existant
     *
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function create()
    {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/personnels/create");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $personnels = $response->json();
        return view('admin.personnel.create', compact('personnels'));
    }

    /**
     * Enregistre un nouvel employé et le connecte à son service
     *
     * @param Request $request Les données de l'employé (compte_id, rôle)
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function store(Request $request)
    {
        $response = Http::withToken(session('token'))->acceptJson()->post("http://host.docker.internal:81/api/admins/personnels/store", [
            'compte_id' => $request->compte_id,
            'role' => $request->role,
        ]);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('personnels.index')
            ->with('success', 'Employé connecté.');
    }

    /**
     * Affiche les détails d'un employé spécifique
     *
     * @param int $id L'identifiant de l'employé
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function show(int $id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/personnels/$id");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $personnel = $response->json();
        return view('admin.personnel.show', compact('personnel'));
    }

    /**
     * Affiche le formulaire de modification d'un employé
     *
     * @param int $id L'identifiant de l'employé
     * @return RedirectResponse|View
     * @throws ConnectionException
     */
    public function edit(int $id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/personnels/$id/edit");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $roles = $response['roles'];
        $personnel = $response['personnel'];

        return view('admin.personnel.edit', compact('roles', 'personnel'));
    }

    /**
     * Met à jour le rôle d'un employé
     *
     * @param Request $request Les données mises à jour
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function update(Request $request)
    {
        $response = Http::withToken(session('token'))->acceptJson()->put("http://host.docker.internal:81/api/admins/personnels/update", [
            'personnel_id' => $request->personnel_id,
            'role' => $request->role,
        ]);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('personnels.index')
            ->with('success', 'Employé mis à jour.');
    }

    /**
     * Déconnecte un employé de son service
     *
     * @param int $id L'identifiant de l'employé
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function destroy(int $id)
    {
        $response = Http::withToken(session('token'))->acceptJson()->delete("http://host.docker.internal:81/api/admins/personnels/$id");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('personnels.index')
            ->with('success', 'Employé déconnecté avec succès.');
    }
}
