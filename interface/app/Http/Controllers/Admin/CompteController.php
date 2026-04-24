<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class CompteController extends Controller
{
    /**
     * @throws ConnectionException
     */
    public function index(Request $request) {
        $query = array_filter([
            'annee_naissance' => $request->annee_naissance,
            'name_contains' => $request->name_contains,
        ]);

        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes", $query);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $comptes = $response->json();
        return view('admin.compte.index', compact('comptes'));
    }

    public function create() {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/create");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $roles = $response->json()['roles'];
        return view('admin.compte.create', compact('roles'));
    }

    /**
     * @throws ConnectionException
     */
    public function store(Request $request) {
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

    public function show($id) {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/{$id}");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $compte = $response->json()['compte'];
        $roles = $response->json()['roles'];
        return view('admin.compte.show', compact('compte', 'roles'));
    }

    public function edit($id) {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/{$id}/edit");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $roles = $response['roles'];
        $compte = $response['compte'];

        return view('admin.compte.edit', compact('roles', 'compte'));
    }

    /**
     * @throws ConnectionException
     */
    public function update(Request $request) {
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
     * @throws ConnectionException
     */
    public function destroy($id) {
        $response = Http::withToken(session('token'))->acceptJson()->delete("http://host.docker.internal:81/api/admins/comptes/{$id}");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('comptes.index')
            ->with('success', 'Compte supprimé avec succès.');
    }
}
