<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function login() {
        return view('auth.login');
    }

    /**
     * @throws ConnectionException
     */
    public function register() {
        try {
            $provinces = collect(Http::get('http://host.docker.internal:81/api/provinces')->json())
                ->pluck('name')
                ->toArray();
        } catch (ConnectionException) {
            $provinces = [];
        }

        try {
            $types = collect(Http::get('http://host.docker.internal:81/api/types')->json())
                ->pluck('name')
                ->toArray() ?? [];
        } catch (ConnectionException) {
            $types = [];
        }

        return view('auth.register', compact('provinces', 'types'));
    }

    /**
     * @throws ConnectionException
     */
    public function sendLoginRequest(Request $request)
    {
        // Validate first (important)
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $response = Http::post('http://host.docker.internal:81/api/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $user = $response['user'] ?? null;

        if ($response->failed() || !$user) {
            return back()
                ->withErrors([
                    'email' => 'Identifiants invalides',
                ])
                ->withInput();
        }

        session([
            'user' => $user,
        ]);

        return redirect()->route('dashboard');
    }

    public function logout()
    {
        session()->forget('user');

        return redirect('login');
    }

    /**
     * @throws ConnectionException
     */
    public function sendRegisterRequest(Request $request)
    {
        $response = Http::post('http://host.docker.internal:81/api/register', [
            'email' => $request->email,
            'password' => $request->password,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'date_naissance' => $request->date_naissance,
            'numero' => $request->numero,
            'rue' => $request->rue,
            'ville' => $request->ville,
            'code_postal' => $request->code_postal,
            'province' => $request->province,
            'type' => $request->type,
            'telephone' => $request->telephone,
        ]);

        if ($response->failed()) {
            $errors = $response->json()['errors'] ?? ['error' => ['Une erreur est survenue']];

            return back()
                ->withErrors($errors)
                ->withInput();
        }

        return redirect()->route('login')
            ->with('success', 'Compte créé, vous pouvez maintenant vous connecter.');
    }
}
