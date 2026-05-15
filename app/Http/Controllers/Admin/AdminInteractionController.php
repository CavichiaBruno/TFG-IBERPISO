<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Controlador para la gestión de Interacciones (likes/dislikes) en el panel de administración.
 *
 * Permite al administrador consultar y gestionar los registros de interacciones
 * que los usuarios realizan con las propiedades en la sección IberScroll.
 * Útil para auditoría y análisis del comportamiento de los usuarios.
 */
class AdminInteractionController extends Controller
{
    /**
     * Muestra el listado de todas las interacciones registradas.
     *
     * Carga la relación con el usuario y la propiedad para mostrar
     * información legible en lugar de IDs numéricos.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $interactions = \App\Models\PropertyInteraction::with(['user', 'property'])->latest()->paginate(20);
        return view('admin.interactions.index', compact('interactions'));
    }

    /**
     * Muestra el formulario para crear una interacción manualmente.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.interactions.create');
    }

    /**
     * Guarda una nueva interacción creada desde el panel de administración.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario_id'   => 'required|exists:usuarios,id',
            'propiedad_id' => 'required|exists:propiedades,id',
            'tipo'         => 'required|string|max:50',
        ]);

        \App\Models\PropertyInteraction::create($validated);
        return redirect()->route('admin.interactions.index')->with('success', 'Interacción creada correctamente.');
    }

    /**
     * Muestra el formulario de edición de una interacción existente.
     *
     * @param  int $id ID de la interacción
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $interaction = \App\Models\PropertyInteraction::findOrFail($id);
        return view('admin.interactions.edit', compact('interaction'));
    }

    /**
     * Actualiza los datos de una interacción existente.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id ID de la interacción
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $interaction = \App\Models\PropertyInteraction::findOrFail($id);

        $validated = $request->validate([
            'usuario_id'   => 'required|exists:usuarios,id',
            'propiedad_id' => 'required|exists:propiedades,id',
            'tipo'         => 'required|string|max:50',
        ]);

        $interaction->update($validated);
        return redirect()->route('admin.interactions.index')->with('success', 'Interacción actualizada correctamente.');
    }

    /**
     * Elimina permanentemente una interacción de la base de datos.
     *
     * @param  int $id ID de la interacción
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        \App\Models\PropertyInteraction::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
