<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seccion;

class SeccionesController extends Controller
{
    public function show(string $seccional)
    {
        $seccion = Seccion::where('seccional', $seccional)->first();
        if (! $seccion) {
            abort(404);
        }

        return [
            'municipio_id' => $seccion->municipio_id,
            'distrito_local' => $seccion->distrito_local,
            'distrito_federal' => $seccion->distrito_federal,
        ];
    }
}

