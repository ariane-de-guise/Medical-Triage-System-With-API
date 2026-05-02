<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

/**
 * Contrôleur de la page d'accueil
 * Affiche la page de bienvenue avec les ressources disponibles
 */
class WelcomeController
{
    /**
     * Affiche la page d'accueil avec la liste des ressources
     *
     * @return View
     */
    public function index()
    {
        try {
            $ressources = collect(Http::get('http://host.docker.internal:81/api/ressources')->json());
        } catch (ConnectionException) {
            $ressources = [];
        }

        return view('welcome', compact('ressources'));
    }
}
