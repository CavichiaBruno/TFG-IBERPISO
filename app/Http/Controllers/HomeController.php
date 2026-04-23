<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Muestra la página de inicio (Landing Page).
     * Recupera los inmuebles destacados y algunas estadísticas generales.
     */
    public function index()
    {
        // Obtenemos las 6 últimas propiedades marcadas como 'destacadas' y 'activas'
        $featured = Property::with(['media'])
            ->active()
            ->featured()
            ->latest()
            ->take(6)
            ->get();

        // Calculamos estadísticas básicas para mostrar en el Hero
        $stats = [
            'properties' => Property::active()->count(),
            'cities'     => Property::active()->distinct('city')->count('city'),
            'users'      => User::where('is_active', true)->count(),
        ];

        // Retornamos la vista principal con los datos compactados
        return view('pages.home', compact('featured', 'stats'));
    }
}
