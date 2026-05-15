<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User;

/**
 * Controlador de la página de inicio (Landing Page) de IberPiso.
 *
 * Se encarga de mostrar la portada de la web con estadísticas generales
 * y las propiedades destacadas. Usa caché para evitar consultas repetidas
 * a la base de datos en cada visita.
 */
class HomeController extends Controller
{
    /**
     * Muestra la página de inicio.
     *
     * Carga las estadísticas generales (propiedades, ciudades, usuarios)
     * usando caché de 15 minutos para mejorar el rendimiento.
     * Las propiedades destacadas se cargan después vía AJAX.
     *
     * @return \Illuminate\View\View
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
     * Devuelve las propiedades destacadas para cargarlas de forma asíncrona (AJAX).
     *
     * Solo responde a peticiones AJAX o que esperen JSON.
     * Los resultados se almacenan en caché 15 minutos para no sobrecargar la base de datos.
     * Devuelve el HTML renderizado del bloque de tarjetas más el conteo total.
     *
     * @return \Illuminate\Http\JsonResponse
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
