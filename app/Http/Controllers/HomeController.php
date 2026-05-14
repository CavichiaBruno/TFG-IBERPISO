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
        // Obtenemos estadísticas básicas (Caché de 15 min)
        $stats = \Illuminate\Support\Facades\Cache::remember('home_stats', 900, function() {
            return [
                'properties' => \DB::table('propiedades')->whereNull('deleted_at')->where('activa', \DB::raw('true'))->count(),
                'cities'     => \DB::table('propiedades')->whereNull('deleted_at')->where('activa', \DB::raw('true'))->distinct('ciudad')->count('ciudad'),
                'users'      => User::where('activo', \DB::raw('true'))->count(),
            ];
        });

        // Para la carga inicial enviamos una colección vacía
        $featured = collect();

        return view('pages.home', compact('featured', 'stats'));
    }

    /**
     * Carga las propiedades destacadas (endpoint AJAX específico)
     */
    public function loadFeatured()
    {
        // Verificamos que es una petición válida (AJAX o que espera JSON)
        if (!request()->ajax() && !request()->wantsJson()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        // Cache de 15 minutos para las propiedades destacadas
        $featured = \Illuminate\Support\Facades\Cache::remember('home_featured', 900, function() {
            $featuredIds = \DB::table('propiedades')
                ->whereNull('deleted_at')
                ->where('activa', \DB::raw('true'))
                ->orderByDesc('created_at')
                ->take(12)
                ->pluck('id');
            
            return Property::with(['medios'])
                ->whereIn('id', $featuredIds)
                ->get();
        });

        // Conteo total para display
        $total = \DB::table('propiedades')
            ->whereNull('deleted_at')
            ->where('activa', \DB::raw('true'))
            ->count();

        return response()->json([
            'html' => view('pages.properties._results_simple', compact('featured'))->render(),
            'count' => $featured->count(),
            'total' => $total,
            'cached' => true
        ]);
    }
}
