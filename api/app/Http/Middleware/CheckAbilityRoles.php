<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Vérifie qu'un utilisateur authentifié possède au moins un des rôles requis.
 */
class CheckAbilityRoles
{
    /**
     * Valide l'accès à une route selon les rôles autorisés.
     *
     * @param Request $request Requête HTTP entrante.
     * @param Closure(Request): (Response) $next Prochaine étape du pipeline middleware.
     * @param string ...$abilities Rôles autorisés pour accéder à la ressource.
     * @return JsonResponse|Response Réponse JSON 401/403 en cas d'échec, sinon réponse du middleware suivant.
     */
    public function handle(Request $request, Closure $next, ...$abilities): JsonResponse|Response
    {
        if (!$request->user() || !$request->user()->currentAccessToken()) {
            return response()->json(['message' => 'Vous devez vous connecter.'], 401);
        }

        foreach ($abilities as $ability) {
            if ($request->user()->hasRole($ability)) {
                return $next($request);
            }
        }

        return response()->json(['message' => 'Vous n\'êtes pas authorizé à voir cette page.'], 403);
    }
}
