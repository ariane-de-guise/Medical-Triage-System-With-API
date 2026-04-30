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
}
