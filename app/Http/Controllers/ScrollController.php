<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyInteraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador para la funcionalidad de IberScroll (Swipe) y Favoritos
 */
class ScrollController extends Controller
{
    /**
     * Muestra la interfaz de descubrimiento tipo "swipe"
     */
    public function index()
    {
        $user = Auth::user();

        // Evitamos mostrar propiedades con las que el usuario ya ha interactuado
        $interactedIds = PropertyInteraction::where('user_id', $user->id)->pluck('property_id');

        // Obtenemos propiedades aleatorias que el usuario aún no ha visto
        $properties = Property::active()
            ->whereNotIn('id', $interactedIds)
            ->with(['media'])
            ->inRandomOrder()
            ->limit(10) 
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'title' => $p->title,
                'price' => $p->formatted_price,
                'location' => $p->city . ', ' . $p->province,
                'surface' => $p->surface_m2,
                'rooms' => $p->rooms,
                'bathrooms' => $p->bathrooms,
                'image' => $p->cover_url,
                'url' => route('properties.show', [$p->id, $p->slug])
            ]);

        return view('pages.scroll', compact('properties'));
    }

    /**
     * Registra si al usuario le gusta (like) o no (dislike) una propiedad
     */
    public function interact(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'type' => 'required|in:like,dislike'
        ]);

        // Guardamos o actualizamos la interacción del usuario
        PropertyInteraction::updateOrCreate(
            ['user_id' => Auth::id(), 'property_id' => $request->property_id],
            ['type' => $request->type]
        );

        return response()->json(['success' => true]);
    }

    /**
     * Muestra la colección de propiedades guardadas por el usuario
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
     * Elimina una propiedad de la sección de favoritos
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
