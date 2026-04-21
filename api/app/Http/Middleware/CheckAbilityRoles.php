<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAbilityRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next, ...$abilities): JsonResponse|Response
    {
        if (! $request->user() || ! $request->user()->currentAccessToken()) {
            return response()->json(['message' => 'Vous devez vous connecter.'], 401);
        }

        foreach ($abilities as $ability) {
            if ($request->user()->tokenCan($ability)) {
                return $next($request);
            }
        }

        return response()->json(['message' => 'Vous n\'êtes pas authorizé à voir cette page.'], 403);
    }
}
