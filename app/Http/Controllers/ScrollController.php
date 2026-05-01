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

        if ($user) {
            // Evitamos mostrar propiedades con las que el usuario ya ha interactuado
            $interactedIds = PropertyInteraction::where('user_id', $user->id)->pluck('property_id');
        } else {
            // Para invitados, usamos la sesión
            $guestInteractions = session('guest_interactions', []);
            $interactedIds = array_keys($guestInteractions);
        }

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

        if (Auth::check()) {
            // Guardamos o actualizamos la interacción del usuario registrado
            PropertyInteraction::updateOrCreate(
                ['user_id' => Auth::id(), 'property_id' => $request->property_id],
                ['type' => $request->type]
            );
        } else {
            // Guardamos en la sesión para invitados
            $guestInteractions = session('guest_interactions', []);
            $guestInteractions[$request->property_id] = $request->type;
            session(['guest_interactions' => $guestInteractions]);
        }

        return response()->json(['success' => true]);
    }


    /**
     * Muestra la colección de propiedades guardadas por el usuario
     */
    public function saved()
    {
        if (Auth::check()) {
            $properties = Auth::user()->favoriteProperties()
                ->with(['media'])
                ->orderBy('property_interactions.created_at', 'desc')
                ->paginate(12);
        } else {
            // Para invitados, obtenemos los IDs con 'like' de la sesión
            $guestInteractions = session('guest_interactions', []);
            $likedIds = array_keys(array_filter($guestInteractions, fn($type) => $type === 'like'));
            
            $properties = Property::whereIn('id', $likedIds)
                ->with(['media'])
                ->paginate(12);
        }

        return view('pages.saved', compact('properties'));
    }

    
    /**
     * Elimina una propiedad de la sección de favoritos
     */
    public function removeFavorite($propertyId)
    {
        if (Auth::check()) {
            PropertyInteraction::where('user_id', Auth::id())
                ->where('property_id', $propertyId)
                ->where('type', 'like')
                ->delete();
        } else {
            $guestInteractions = session('guest_interactions', []);
            if (isset($guestInteractions[$propertyId])) {
                unset($guestInteractions[$propertyId]);
                session(['guest_interactions' => $guestInteractions]);
            }
        }

        return back()->with('success', 'Propiedad eliminada de tus guardados.');
    }

}
