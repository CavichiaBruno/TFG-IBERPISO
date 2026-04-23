<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyInteraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScrollController extends Controller
{
    /**
     * Muestra la interfaz de "Scroll" tipo Tinder.
     */
    public function index()
    {
        $user = Auth::user();

        // Obtener IDs de propiedades con las que ya interactuó el usuario
        $interactedIds = PropertyInteraction::where('user_id', $user->id)
            ->pluck('property_id');

        // Obtener propiedades activas con las que NO ha interactuado
        $properties = Property::active()
            ->whereNotIn('id', $interactedIds)
            ->with(['media', 'user'])
            ->inRandomOrder()
            ->limit(10) // Cargamos 10 inicialmente
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'title' => $p->title,
                    'price' => $p->formatted_price,
                    'location' => $p->city . ', ' . $p->province,
                    'surface' => $p->surface_m2,
                    'rooms' => $p->rooms,
                    'bathrooms' => $p->bathrooms,
                    'image' => $p->cover_url,
                    'slug' => $p->slug,
                    'url' => route('properties.show', [$p->id, $p->slug])
                ];
            });

        return view('pages.scroll', compact('properties'));
    }

    /**
     * Guarda una interacción (like/dislike).
     */
    public function interact(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'type' => 'required|in:like,dislike'
        ]);

        PropertyInteraction::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'property_id' => $request->property_id
            ],
            [
                'type' => $request->type
            ]
        );

        return response()->json(['success' => true]);
    }

    /**
     * Muestra la lista de propiedades guardadas (favoritos).
     */
    public function saved()
    {
        $properties = Auth::user()->favoriteProperties()
            ->with(['media'])
            ->orderBy('property_interactions.created_at', 'desc')
            ->paginate(12);

        return view('pages.saved', compact('properties'));
    }
    
    /**
     * Elimina una propiedad de la lista de guardados.
     */
    public function removeFavorite($propertyId)
    {
        PropertyInteraction::where('user_id', Auth::id())
            ->where('property_id', $propertyId)
            ->where('type', 'like')
            ->delete();

        return back()->with('success', 'Propiedad eliminada de tus guardados.');
    }
}
