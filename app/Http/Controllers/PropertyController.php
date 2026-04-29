<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with(['media'])->active();

        if ($request->filled('operacion')) {
            $query->filterByOperation($request->operacion);
        }
        if ($request->filled('tipo')) {
            $types = is_array($request->tipo) ? $request->tipo : [$request->tipo];
            $query->whereIn('property_type', $types);
        }
        if ($request->filled('precio_min')) {
            $query->where('price', '>=', (float) $request->precio_min);
        }
        if ($request->filled('precio_max')) {
            $query->where('price', '<=', (float) $request->precio_max);
        }
        if ($request->filled('superficie_min')) {
            $query->where('surface_m2', '>=', (float) $request->superficie_min);
        }
        if ($request->filled('superficie_max')) {
            $query->where('surface_m2', '<=', (float) $request->superficie_max);
        }
        if ($request->filled('habitaciones')) {
            $rooms = (int) $request->habitaciones;
            $rooms >= 5 ? $query->where('rooms', '>=', 5) : $query->where('rooms', $rooms);
        }
        if ($request->filled('banos')) {
            $baths = (int) $request->banos;
            $baths >= 3 ? $query->where('bathrooms', '>=', 3) : $query->where('bathrooms', $baths);
        }
        foreach (['has_elevator','has_parking','has_terrace','has_garden','has_pool','air_conditioning'] as $a) {
            if ($request->boolean($a)) {
                $query->where($a, true);
            }
        }
        if ($request->filled('provincia')) {
            $query->where('province', $request->provincia);
        }
        if ($request->filled('ciudad')) {
            $query->where('city', 'like', '%' . $request->ciudad . '%');
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%")
                    ->orWhere('province', 'like', "%{$q}%")
                    ->orWhere('address', 'like', "%{$q}%")
                    ->orWhere('postal_code', 'like', "%{$q}%");
            });
        }

        $sort = $request->get('orden', 'reciente');
        match ($sort) {
            'precio_asc'  => $query->orderBy('price', 'asc'),
            'precio_desc' => $query->orderBy('price', 'desc'),
            'superficie'  => $query->orderBy('surface_m2', 'desc'),
            default       => $query->latest(),
        };

        $properties = $query->paginate(12)->withQueryString();
        $provinces  = Property::active()->distinct()->orderBy('province')->pluck('province');

        if ($request->ajax()) {
            return response()->json([
                'html'       => view('pages.properties._results', compact('properties'))->render(),
                'total'      => $properties->total(),
                'pagination' => $properties->links()->toHtml(),
            ]);
        }

        return view('pages.properties.index', compact('properties', 'provinces'));
    }

    public function show(int $id, string $slug)
    {
        // We allow viewing the property even if it's not active if requested by ID
        // (This helps with admin previews)
        $property = Property::with(['media', 'user'])->findOrFail($id);

        $related = Property::with(['media'])
            ->active()
            ->where('id', '!=', $property->id)
            ->where('city', $property->city)
            ->take(3)->get();

        return view('pages.properties.show', compact('property', 'related'));
    }
}
