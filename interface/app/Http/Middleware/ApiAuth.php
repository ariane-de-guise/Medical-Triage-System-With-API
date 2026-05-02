<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware d'authentification API
 * Vérifie que l'utilisateur est connecté avant d'accéder aux routes protégées
 */
class ApiAuth
{
    /**
     * Traite une requête entrante
     * Redirige vers la page de connexion si l'utilisateur n'est pas authentifié
     *
     * @param Request $request La requête HTTP entrante
     * @param Closure(Request): (Response) $next Le callback pour passer au middleware suivant
     * @return RedirectResponse|Response La réponse HTTP ou une redirection
     */
    public function handle(Request $request, Closure $next): RedirectResponse|Response
    {
        if (!session('user')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
