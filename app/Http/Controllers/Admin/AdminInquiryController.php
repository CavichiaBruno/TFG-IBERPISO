<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class AdminInquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inquiry::with(['propiedad', 'usuario'])->latest();

        $status = $request->get('estado', 'todas');
        if ($status !== 'todas') {
            $query->where('estado', $status);
        }

        $inquiries   = $query->paginate(20)->withQueryString();
        $unreadCount = Inquiry::unread()->count();

        return view('admin.inquiries.index', compact('inquiries', 'unreadCount'));
    }

    public function create()
    {
        return view('admin.inquiries.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'propiedad_id'       => 'nullable|exists:propiedades,id',
            'usuario_id'         => 'nullable|exists:usuarios,id',
            'nombre_visitante'   => 'required_without:usuario_id|string|max:100',
            'correo_visitante'   => 'required_without:usuario_id|email|max:150',
            'telefono_visitante' => 'nullable|string|max:20',
            'mensaje'            => 'required|string',
            'estado'             => 'required|in:pendiente,leida,respondida',
        ]);

        $validated['leida'] = $validated['estado'] !== 'pendiente';
        Inquiry::create($validated);

        return redirect()->route('admin.inquiries.index')->with('success', 'Consulta creada correctamente.');
    }

    public function edit(int $id)
    {
        $inquiry = Inquiry::findOrFail($id);
        return view('admin.inquiries.edit', compact('inquiry'));
    }

    public function update(Request $request, int $id)
    {
        $inquiry = Inquiry::findOrFail($id);

        $validated = $request->validate([
            'propiedad_id'       => 'nullable|exists:propiedades,id',
            'usuario_id'         => 'nullable|exists:usuarios,id',
            'nombre_visitante'   => 'required_without:usuario_id|string|max:100',
            'correo_visitante'   => 'required_without:usuario_id|email|max:150',
            'telefono_visitante' => 'nullable|string|max:20',
            'mensaje'            => 'required|string',
            'estado'             => 'required|in:pendiente,leida,respondida',
        ]);

        $validated['leida'] = $validated['estado'] !== 'pendiente';
        $inquiry->update($validated);

        return redirect()->route('admin.inquiries.index')->with('success', 'Consulta actualizada.');
    }

    public function updateStatus(Request $request, int $id)
    {
        $inquiry = Inquiry::findOrFail($id);

        $request->validate(['estado' => 'required|in:pendiente,leida,respondida']);

        $inquiry->update([
            'estado'  => $request->estado,
            'leida' => $request->estado !== 'pendiente',
        ]);

        return response()->json(['success' => true, 'estado' => $inquiry->estado]);
    }

    public function destroy(int $id)
    {
        Inquiry::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
