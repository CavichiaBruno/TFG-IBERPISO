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
            $interactedIds = PropertyInteraction::where('usuario_id', $user->id)->pluck('propiedad_id');
        } else {
            // Para invitados, usamos la sesión
            $guestInteractions = session('guest_interactions', []);
            $interactedIds = array_keys($guestInteractions);
        }

        // Obtenemos propiedades aleatorias que el usuario aún no ha visto
        $properties = Property::active()
            ->whereNotIn('id', $interactedIds)
            ->with(['medios'])
            ->inRandomOrder()
            ->limit(10) 
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'titulo' => $p->titulo,
                'precio' => $p->formatted_price,
                'ubicacion' => $p->ciudad . ', ' . $p->provincia,
                'superficie' => $p->superficie_m2,
                'habitaciones' => $p->habitaciones,
                'banos' => $p->banos,
                'imagen' => $p->cover_url,
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
            'propiedad_id' => 'required|exists:propiedades,id',
            'tipo' => 'required|in:like,dislike'
        ]);

        if (Auth::check()) {
            // Guardamos o actualizamos la interacción del usuario registrado
            PropertyInteraction::updateOrCreate(
                ['usuario_id' => Auth::id(), 'propiedad_id' => $request->propiedad_id],
                ['tipo' => $request->tipo]
            );
        } else {
            // Guardamos en la sesión para invitados
            $guestInteractions = session('guest_interactions', []);
            $guestInteractions[$request->propiedad_id] = $request->tipo;
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
                ->with(['medios'])
                ->orderBy('interacciones_propiedades.created_at', 'desc')
                ->paginate(12);
        } else {
            // Para invitados, obtenemos los IDs con 'like' de la sesión
            $guestInteractions = session('guest_interactions', []);
            $likedIds = array_keys(array_filter($guestInteractions, fn($type) => $type === 'like'));
            
            $properties = Property::whereIn('id', $likedIds)
                ->with(['medios'])
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
            PropertyInteraction::where('usuario_id', Auth::id())
                ->where('propiedad_id', $propertyId)
                ->where('tipo', 'like')
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
