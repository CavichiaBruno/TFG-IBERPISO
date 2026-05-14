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
                'properties' => \DB::table('propiedades')->whereNull('deleted_at')->where('activa', true)->count(),
                'cities'     => \DB::table('propiedades')->whereNull('deleted_at')->where('activa', true)->distinct('ciudad')->count('ciudad'),
                'users'      => User::where('activo', true)->count(),
            ];
        });

        // Si es una petición AJAX (pedida desde el frontend tras cargar el esqueleto)
        if (request()->ajax()) {
            $featured = \Illuminate\Support\Facades\Cache::remember('home_featured', 900, function() {
                $featuredIds = \DB::table('propiedades')
                    ->whereNull('deleted_at')
                    ->where('activa', true)
                    ->orderByDesc('created_at')
                    ->take(12)
                    ->pluck('id');
                
                return Property::with(['medios'])
                    ->whereIn('id', $featuredIds)
                    ->get();
            });

            return response()->json([
                'html' => view('pages.properties._results_simple', compact('featured'))->render()
            ]);
        }

        // Para la carga inicial enviamos una colección vacía
        $featured = collect();

        return view('pages.home', compact('featured', 'stats'));
    }
}
