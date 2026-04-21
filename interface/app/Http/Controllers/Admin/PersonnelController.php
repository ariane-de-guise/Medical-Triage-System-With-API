<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class PersonnelController extends Controller
{
    /**
     * @throws ConnectionException
     */
    public function index() {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/personnels");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $personnels = $response->json();
        return view('admin.personnel.index', compact('personnels'));
    }

    public function create() {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/personnels/create");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $personnels = $response->json();
        return view('admin.personnel.create', compact('personnels'));
    }

    public function store(Request $request) {
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

    public function show($id) {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/personnels/{$id}");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $personnel = $response->json();
        return view('admin.personnel.show', compact('personnel'));
    }

    public function edit($id) {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/personnels/{$id}/edit");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $roles = $response['roles'];
        $personnel = $response['personnel'];

        return view('admin.personnel.edit', compact('roles', 'personnel'));
    }

    /**
     * @throws ConnectionException
     */
    public function update(Request $request) {
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
     * @throws ConnectionException
     */
    public function destroy($id) {
        $response = Http::withToken(session('token'))->acceptJson()->delete("http://host.docker.internal:81/api/admins/personnels/{$id}");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('personnels.index')
            ->with('success', 'Employé déconnecté avec succès.');
    }
}
