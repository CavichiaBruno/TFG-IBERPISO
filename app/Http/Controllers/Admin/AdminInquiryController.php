<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;

/**
 * Controlador para la gestión de Consultas desde el panel de administración.
 *
 * Permite al administrador ver, filtrar por estado, cambiar el estado
 * y eliminar las consultas de contacto que los usuarios envían
 * desde las fichas de propiedades.
 */
class AdminInquiryController extends Controller
{
    /**
     * Muestra el listado de consultas con filtro por estado.
     *
     * Por defecto muestra todas. Se puede filtrar por: pendiente, leida, respondida.
     * También muestra el contador de consultas no leídas para el sidebar.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
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

    /**
     * Muestra el formulario para crear una nueva consulta manualmente.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.inquiries.create');
    }

    /**
     * Guarda una nueva consulta creada desde el panel de administración.
     *
     * Se puede asociar a un usuario registrado o a un visitante anónimo.
     * Si no se especifica usuario, los campos de nombre y correo son obligatorios.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Muestra el formulario de edición de una consulta existente.
     *
     * @param  int $id ID de la consulta
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $inquiry = Inquiry::findOrFail($id);
        return view('admin.inquiries.edit', compact('inquiry'));
    }

    /**
     * Actualiza los datos de una consulta existente.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id ID de la consulta
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Actualiza solo el estado de una consulta (endpoint rápido para la tabla).
     *
     * Usado por el botón de cambio de estado en la vista de listado,
     * sin necesidad de entrar al formulario completo de edición.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id ID de la consulta
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Elimina permanentemente una consulta de la base de datos.
     *
     * @param  int $id ID de la consulta
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        Inquiry::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
