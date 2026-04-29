<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use Illuminate\Http\Request;

/**
 * Controlador de Administración para la gestión de Propiedades (CRUD)
 */
class AdminPropertyController extends Controller
{
    /**
     * Lista todas las propiedades en el panel administrativo con filtros
     */
    public function index(Request $request)
    {
        // Cargamos propiedades incluyendo las borradas lógicamente para gestión
        $query = Property::with(['media', 'user'])->withTrashed();

        // Búsqueda por texto (título, ciudad o dirección)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%")
                    ->orWhere('address', 'like', "%{$q}%");
            });
        }

        // Sistema de pestañas de filtrado (Activas, Inactivas, Destacadas, etc.)
        $filter = $request->get('filtro', 'all');
        match ($filter) {
            'activas'    => $query->where('is_active', true)->whereNull('deleted_at'),
            'inactivas'  => $query->where('is_active', false)->whereNull('deleted_at'),
            'destacadas' => $query->where('is_featured', true)->whereNull('deleted_at'),
            'venta'      => $query->where('operation_type', 'venta')->whereNull('deleted_at'),
            'alquiler'   => $query->where('operation_type', 'alquiler')->whereNull('deleted_at'),
            default      => $query->whereNull('deleted_at'),
        };

        // Paginamos y contamos consultas no leídas para el contador del sidebar
        $properties = $query->latest()->paginate(15)->withQueryString();
        $unreadCount = \App\Models\Inquiry::unread()->count();

        // Respuesta para actualización asíncrona de la tabla
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.properties._table', compact('properties'))->render(),
            ]);
        }

        return view('admin.properties.index', compact('properties', 'unreadCount'));
    }

    /**
     * Muestra el formulario para crear una nueva propiedad
     */
    public function create()
    {
        $unreadCount = \App\Models\Inquiry::unread()->count();
        return view('admin.properties.create', compact('unreadCount'));
    }

    /**
     * Procesa el guardado de una nueva propiedad en la base de datos
     */
    public function store(StorePropertyRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id(); // Asignamos el autor (agente actual)
        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        $property = Property::create($data);

        return redirect()
            ->route('admin.properties.edit', $property->id)
            ->with('success', 'Propiedad creada correctamente. Ahora puedes subir imágenes.');
    }

    /**
     * Muestra el formulario de edición de una propiedad existente
     */
    public function edit(int $id)
    {
        $property = Property::with(['media'])->findOrFail($id);
        $unreadCount = \App\Models\Inquiry::unread()->count();
        return view('admin.properties.edit', compact('property', 'unreadCount'));
    }

    /**
     * Actualiza los datos de una propiedad en la base de datos
     */
    public function update(UpdatePropertyRequest $request, int $id)
    {
        $property = Property::findOrFail($id);
        $data = $request->validated();
        $data['is_active']   = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        $property->update($data);

        return redirect()
            ->route('admin.properties.edit', $property->id)
            ->with('success', 'Propiedad actualizada correctamente.');
    }

    /**
     * Elimina (borrado lógico) una propiedad
     */
    public function destroy(int $id)
    {
        $property = Property::findOrFail($id);
        $property->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Cambia el estado de visibilidad (Activo/Inactivo) rápidamente
     */
    public function toggleActive(int $id)
    {
        $property = Property::findOrFail($id);
        $property->update(['is_active' => !$property->is_active]);
        return response()->json(['success' => true, 'is_active' => $property->is_active]);
    }
}
