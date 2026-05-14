<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\PropertyMedia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserPropertyController extends Controller
{
    public function create()
    {
        return view('pages.properties.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:200',
            'descripcion' => 'required|string|min:100',
            'tipo_propiedad' => 'required|string',
            'tipo_operacion' => 'required|in:venta,alquiler',
            'precio' => 'required|numeric|min:0',
            'superficie_m2' => 'required|numeric|min:0',
            'habitaciones' => 'required|integer|min:0',
            'banos' => 'required|integer|min:0',
            'direccion' => 'required|string',
            'ciudad' => 'required|string',
            'provincia' => 'required|string',
            'codigo_postal' => 'required|string|size:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'certificado_energetico_archivo' => 'nullable|file|mimes:pdf|max:5120'
        ]);

        // Generar slug automáticamente
        $baseSlug = Str::slug($validated['titulo']);
        $slug = $baseSlug;
        $counter = 1;
        while (Property::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $property = new Property();
        $property->fill($validated);
        $property->slug = $slug;
        $property->usuario_id = Auth::id(); // Asignar al usuario autenticado
        // Las propiedades de los usuarios están activas por defecto
        $property->activa = \DB::raw('true');
        
        // Asegurar que el precio se guarda como número decimal
        $property->precio = (float) $validated['precio'];
        $property->superficie_m2 = (float) $validated['superficie_m2']; 
        
        // Valores por defecto para campos booleanos si no están presentes
        $booleanFields = ['destacada', 'tiene_ascensor', 'tiene_parking', 'tiene_terraza', 'tiene_jardin', 'tiene_piscina', 'aire_acondicionado'];
        foreach ($booleanFields as $field) {
            $property->$field = $request->has($field) ? \DB::raw('true') : \DB::raw('false');
        }

        // Gestión del certificado energético (PDF)
        if ($request->hasFile('certificado_energetico_archivo')) {
            $property->certificado_energetico_archivo = $request->file('certificado_energetico_archivo')->store('certificates', 'public');
        }

        $property->save();

        // Gestión de la subida de imágenes
        if ($request->hasFile('images')) {
            $isFirst = true;
            foreach ($request->file('images') as $image) {
                // Guardar la imagen en storage/public/properties
                $path = $image->store('properties', 'public');

                PropertyMedia::create([
                    'propiedad_id' => $property->id,
                    'ruta_archivo' => $path,
                    'tipo_archivo' => 'imagen',
                    'tipo_mime' => $image->getMimeType(),
                    'tamano_archivo_kb' => (int) ceil($image->getSize() / 1024),
                    'nombre_original' => $image->getClientOriginalName(),
                    'es_portada' => $isFirst ? \DB::raw('true') : \DB::raw('false'),
                ]);

                $isFirst = false;
            }
        }

        return redirect()->route('properties.show', [$property->id, $property->slug])
                         ->with('success', '¡Publicación creada exitosamente!');
    }
    public function index()
    {
        $properties = Property::where('usuario_id', Auth::id())
            ->with(['medios' => function($query) {
                $query->where('tipo_archivo', 'imagen')->orderBy('es_portada', 'desc');
            }])
            ->latest()
            ->paginate(12);

        return view('pages.properties.user_index', compact('properties'));
    }

    public function toggleActive($id)
    {
        $property = Property::where('usuario_id', Auth::id())->findOrFail($id);
        $property->activa = $property->activa ? \DB::raw('false') : \DB::raw('true');
        $property->save();

        return back()->with('success', $property->activa ? 'Anuncio activado.' : 'Anuncio desactivado.');
    }

    public function destroy($id)
    {
        $property = Property::where('usuario_id', Auth::id())->findOrFail($id);
        $property->delete();

        return redirect()->route('user.properties.index')->with('success', 'Anuncio eliminado correctamente.');
    }
}
