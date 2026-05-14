<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminInteractionController extends Controller
{
    public function index()
    {
        $interactions = \App\Models\PropertyInteraction::with(['user', 'property'])->latest()->paginate(20);
        return view('admin.interactions.index', compact('interactions'));
    }

    public function create()
    {
        return view('admin.interactions.create');
    }

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

    public function edit(int $id)
    {
        $interaction = \App\Models\PropertyInteraction::findOrFail($id);
        return view('admin.interactions.edit', compact('interaction'));
    }

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

    public function destroy(int $id)
    {
        \App\Models\PropertyInteraction::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
