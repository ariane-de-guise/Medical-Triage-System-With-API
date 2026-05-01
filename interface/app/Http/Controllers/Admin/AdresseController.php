<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class AdresseController extends Controller
{
    /**
     * @throws ConnectionException
     */
    public function index(Request $request, $compte_id) {
        $query = array_filter([
            'province' => $request->province,
            'type' => $request->type,
        ]);

        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/{$compte_id}/adresses", $query);

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $adresses = $response->json()['adresses'];
        $provinces = $response->json()['provinces'];
        $types = $response->json()['types'];

        return view('admin.adresse.index', compact('adresses', 'provinces', 'types', 'compte_id'));
    }

    /**
     * @throws ConnectionException
     */
    public function create($compte_id) {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/{$compte_id}/adresses/create");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $provinces = $response->json()['provinces'];
        $types = $response->json()['types'];

        return view('admin.adresse.create', compact('compte_id', 'provinces', 'types'));
    }

    /**
     * @throws ConnectionException
     */
    public function store(Request $request, $compte_id) {
        $response = Http::withToken(session('token'))->acceptJson()->post("http://host.docker.internal:81/api/admins/comptes/{$compte_id}/adresses/store", [
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

        return redirect()->route('adresses.index', compact('compte_id'))->with('success', 'Visite créée avec succès');
    }

    /**
     * @throws ConnectionException
     */
    public function show($compte_id, $id) {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/{$compte_id}/adresses/{$id}");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $adresse = $response->json()['adresse'];

        return view('admin.adresse.show', compact('adresse', 'compte_id'));
    }

    /**
     * @throws ConnectionException
     */
    public function edit($compte_id, $id) {
        $response = Http::withToken(session('token'))->acceptJson()->get("http://host.docker.internal:81/api/admins/comptes/{$compte_id}/adresses/{$id}/edit");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        $adresse = $response->json()['adresse'];
        $provinces = $response->json()['provinces'];
        $types = $response->json()['types'];

        return view('admin.adresse.edit', compact('compte_id', 'adresse', 'provinces', 'types'));
    }

    /**
     * @throws ConnectionException
     */
    public function update(Request $request, $compte_id) {
        $response = Http::withToken(session('token'))->acceptJson()->put("http://host.docker.internal:81/api/admins/comptes/{$compte_id}/adresses/update", [
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

        return redirect()->route('adresses.index', compact('compte_id'))->with('success', 'Visite mise à jour avec succès');
    }

    /**
     * @throws ConnectionException
     */
    public function destroy($compte_id, $id) {
        $response = Http::withToken(session('token'))->acceptJson()->delete("http://host.docker.internal:81/api/admins/comptes/{$compte_id}/adresses/{$id}");

        if ($response->failed()) {
            return redirect()->back()->with('error', $response->json()['message']);
        }

        return redirect()->route('adresses.index', $compte_id)
            ->with('success', 'Visite supprimée avec succès.');
    }
}
