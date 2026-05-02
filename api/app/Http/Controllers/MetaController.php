<?php

namespace App\Http\Controllers;

use App\Algorithms\Constants;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Fournit les métadonnées de référence consommées par le front-end.
 */
class MetaController extends Controller
{
    /**
     * Retourne la liste des provinces disponibles.
     *
     * @return JsonResponse
     */
    public function provinces()
    {
        return response()->json(
            array_map(fn($province) => ['name' => $province], Constants::PROVINCES)
        );
    }

    /**
     * Retourne la liste des types d'adresses disponibles.
     *
     * @return JsonResponse
     */
    public function types()
    {
        return response()->json(
            array_map(fn($type) => ['name' => $type], Constants::TYPES)
        );
    }

    /**
     * Retourne les ressources d'aide classées par catégorie.
     *
     * @return JsonResponse
     */
    public function resources()
    {
        return response()->json(
            Constants::RESSOURCES
        );
    }
}
