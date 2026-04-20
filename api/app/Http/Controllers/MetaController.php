<?php

namespace App\Http\Controllers;

use App\Algorithms\Constants;
use Illuminate\Routing\Controller;

class MetaController extends Controller
{
    public function provinces()
    {
        return response()->json(
            array_map(fn($province) => ['name' => $province], Constants::PROVINCES)
        );
    }

    public function types()
    {
        return response()->json(
            array_map(fn($type) => ['name' => $type], Constants::TYPES)
        );
    }
}
