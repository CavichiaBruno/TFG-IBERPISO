<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use Illuminate\Http\Request;

class AdminPropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with(['media', 'user'])->withTrashed();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%")
                    ->orWhere('address', 'like', "%{$q}%");
            });
        }

        $filter = $request->get('filtro', 'all');
        match ($filter) {
            'activas'    => $query->where('is_active', true)->whereNull('deleted_at'),
            'inactivas'  => $query->where('is_active', false)->whereNull('deleted_at'),
            'destacadas' => $query->where('is_featured', true)->whereNull('deleted_at'),
            'venta'      => $query->where('operation_type', 'venta')->whereNull('deleted_at'),
            'alquiler'   => $query->where('operation_type', 'alquiler')->whereNull('deleted_at'),
            default      => $query->whereNull('deleted_at'),
        };

        $properties = $query->latest()->paginate(15)->withQueryString();
        $unreadCount = \App\Models\Inquiry::unread()->count();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.properties._table', compact('properties'))->render(),
            ]);
        }

        return view('admin.properties.index', compact('properties', 'unreadCount'));
    }

    public function create()
    {
        $unreadCount = \App\Models\Inquiry::unread()->count();
        return view('admin.properties.create', compact('unreadCount'));
    }

    public function store(StorePropertyRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        $property = Property::create($data);

        return redirect()
            ->route('admin.properties.edit', $property->id)
            ->with('success', 'Propiedad creada correctamente. Ahora puedes subir imágenes.');
    }

    public function edit(int $id)
    {
        $property = Property::with(['media'])->findOrFail($id);
        $unreadCount = \App\Models\Inquiry::unread()->count();
        return view('admin.properties.edit', compact('property', 'unreadCount'));
    }

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

    public function destroy(int $id)
    {
        $property = Property::findOrFail($id);
        $property->delete();

        return response()->json(['success' => true]);
    }

    public function toggleActive(int $id)
    {
        $property = Property::findOrFail($id);
        $property->update(['is_active' => !$property->is_active]);
        return response()->json(['success' => true, 'is_active' => $property->is_active]);
    }
}
