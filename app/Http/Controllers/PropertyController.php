<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

/**
 * Controlador para la gestión pública de propiedades (Listado y Detalle)
 */
class PropertyController extends Controller
{
    /**
     * Muestra el listado de propiedades con filtros aplicados
     */
    public function index(Request $request)
    {
        // Iniciamos la consulta cargando la relación de media y filtrando por activas
        $query = Property::with(['medios'])->active();

        // Filtro por tipo de operación (Venta/Alquiler)
        if ($request->filled('operacion')) {
            $query->filterByOperation($request->operacion);
        }
        
        // Filtro por tipo de inmueble
        if ($request->filled('tipo')) {
            $types = is_array($request->tipo) ? $request->tipo : [$request->tipo];
            $query->whereIn('tipo_propiedad', $types);
        }
        
        // Filtros de rango de precio
        if ($request->filled('precio_min')) $query->where('precio', '>=', (float) $request->precio_min);
        if ($request->filled('precio_max')) $query->where('precio', '<=', (float) $request->precio_max);
        
        // Filtros de superficie
        if ($request->filled('superficie_min')) $query->where('superficie_m2', '>=', (float) $request->superficie_min);
        if ($request->filled('superficie_max')) $query->where('superficie_m2', '<=', (float) $request->superficie_max);
        
        // Filtro de habitaciones y baños
        if ($request->filled('habitaciones')) {
            $rooms = (int) $request->habitaciones;
            $rooms >= 5 ? $query->where('habitaciones', '>=', 5) : $query->where('habitaciones', $rooms);
        }
        if ($request->filled('banos')) {
            $baths = (int) $request->banos;
            $baths >= 3 ? $query->where('banos', '>=', 3) : $query->where('banos', $baths);
        }
        
        // Filtro de características adicionales (ascensor, parking, etc.)
        foreach (['tiene_ascensor','tiene_parking','tiene_terraza','tiene_jardin','tiene_piscina','aire_acondicionado'] as $a) {
            if ($request->boolean($a)) $query->where($a, \DB::raw('true'));
        }
        
        // Filtro por ubicación
        if ($request->filled('provincia')) $query->where('provincia', 'ilike', $request->provincia);
        if ($request->filled('ciudad')) $query->where('ciudad', 'ilike', '%' . $request->ciudad . '%');
        
        // Búsqueda general de texto
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('titulo', 'ilike', "%{$q}%")
                    ->orWhere('ciudad', 'ilike', "%{$q}%")
                    ->orWhere('provincia', 'ilike', "%{$q}%")
                    ->orWhere('direccion', 'ilike', "%{$q}%")
                    ->orWhere('codigo_postal', 'ilike', "%{$q}%");
            });
        }

        // Ordenación de resultados
        $sort = $request->get('orden', 'reciente');
        match ($sort) {
            'precio_asc'  => $query->orderBy('precio', 'asc'),
            'precio_desc' => $query->orderBy('precio', 'desc'),
            'superficie'  => $query->orderBy('superficie_m2', 'desc'),
            default       => $query->latest(),
        };

        // Caché de provincias para mejorar performance (1 hora)
        $provinces  = \Illuminate\Support\Facades\Cache::remember('provinces_list', 3600, function() {
            return Property::active()->distinct()->orderBy('provincia')->pluck('provincia');
        });

        // Si la petición es AJAX, devolvemos el HTML parcial de los resultados
        if ($request->ajax()) {
            $properties = $query->paginate(12)->withQueryString();
            return response()->json([
                'html' => view('pages.properties._results', compact('properties'))->render(),
                'pagination' => $properties->links('components.pagination')->toHtml(),
                'count' => $properties->total()
            ]);
        }

        // Para la carga inicial (no AJAX), enviamos una colección vacía para que el frontend pida los datos
        $properties = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12);
        
        return view('pages.properties.index', compact('properties', 'provinces'));
    }

    /**
     * Muestra la ficha detallada de una propiedad
     */
    public function show(int $id, string $slug)
    {
        // Buscamos la propiedad por ID (incluyendo inactivas para previsualización de admin)
        $property = Property::with(['medios', 'usuario'])->findOrFail($id);

        // Obtenemos propiedades relacionadas en la misma ciudad
        $related = Property::with(['medios'])
            ->active()
            ->where('id', '!=', $property->id)
            ->where('ciudad', $property->ciudad)
            ->take(3)->get();

        return view('pages.properties.show', compact('property', 'related'));
    }
}
