<?php

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

if (!function_exists('calcularDiasHabiles')) {
    function calcularDiasHabiles($fechaIngreso)
    {
        $fechaInicio = Carbon::parse($fechaIngreso);
        $fechaActual = Carbon::now();
        $diasHabiles = 0;

        // Obtener festivos desde la API
        $festivos = collect(Http::get("https://api-colombia.com/api/v1/holiday/year/2025")->json())
                    ->pluck('date')->map(fn($date) => Carbon::parse($date)->toDateString());

        while ($fechaInicio->lte($fechaActual)) {
            if (!$fechaInicio->isWeekend() && !$festivos->contains($fechaInicio->toDateString())) {
                $diasHabiles++;
            }
            $fechaInicio->addDay();
        }

        return $diasHabiles;
    }
}
