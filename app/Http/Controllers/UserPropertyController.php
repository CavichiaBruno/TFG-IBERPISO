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
        // 1. Validación de los datos recibidos del formulario
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

        // 2. Generación automática del "Slug" (URL amigable)
        // Si el título es "Piso en Madrid", el slug será "piso-en-madrid"
        $baseSlug = Str::slug($validated['titulo']);
        $slug = $baseSlug;
        $counter = 1;
        while (Property::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        // 3. Creación de la instancia del modelo y asignación de datos básicos
        $property = new Property();
        $property->fill($validated);
        $property->slug = $slug;
        $property->usuario_id = Auth::id(); // Vinculamos la propiedad al usuario logueado
        $property->activa = \DB::raw('true'); // Por defecto, se publica activa
        
        // Conversión explícita de tipos numéricos
        $property->precio = (float) $validated['precio'];
        $property->superficie_m2 = (float) $validated['superficie_m2']; 
        
        // 4. Procesamiento de campos booleanos (Checkboxes)
        // Si el checkbox no viene en el request, se marca como false
        $booleanFields = ['destacada', 'tiene_ascensor', 'tiene_parking', 'tiene_terraza', 'tiene_jardin', 'tiene_piscina', 'aire_acondicionado'];
        foreach ($booleanFields as $field) {
            $property->$field = $request->has($field) ? \DB::raw('true') : \DB::raw('false');
        }

        // 5. Gestión del Certificado Energético (PDF)
        if ($request->hasFile('certificado_energetico_archivo')) {
            $property->certificado_energetico_archivo = $request->file('certificado_energetico_archivo')->store('certificates', 'public');
        }

        // Guardamos la propiedad en la base de datos
        $property->save();

        // 6. Procesamiento de la Galería de Imágenes
        if ($request->hasFile('images')) {
            $this->uploadPropertyImages($property, $request->file('images'));
        }

        return redirect()->route('properties.show', [$property->id, $property->slug])
                         ->with('success', '¡Publicación creada exitosamente!');
    }

    /**
     * Procesa y guarda las imágenes de una propiedad.
     * Separado en un método privado para reducir la complejidad del controlador principal.
     */
    private function uploadPropertyImages($property, array $images)
    {
        $isFirst = true;
        foreach ($images as $image) {
            // Guardar el archivo físico en storage/app/public/properties
            $path = $image->store('properties', 'public');

            // Crear el registro en la tabla medios_propiedades
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
    public function index()
    {
        $properties = Property::where('usuario_id', Auth::id())
            ->with(['coverImage'])
            ->select('id', 'titulo', 'precio', 'ciudad', 'activa', 'slug', 'created_at')
            ->latest()
            ->paginate(12);

        return view('pages.properties.user_index', compact('properties'));
    }

    public function inquiries()
    {
        $inquiries = Auth::user()->receivedInquiries()
            ->with(['property' => function($q) {
                $q->select('id', 'titulo', 'slug');
            }])
            ->latest()
            ->paginate(15);

        return view('pages.properties.user_inquiries', compact('inquiries'));
    }

    public function markAsRead($id)
    {
        $inquiry = Auth::user()->receivedInquiries()->findOrFail($id);
        $inquiry->update(['leida' => \DB::raw('true')]);

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $property = Property::where('usuario_id', Auth::id())->with('medios')->findOrFail($id);
        return view('pages.properties.edit', compact('property'));
    }

    public function update(Request $request, $id)
    {
        // 1. Buscar la propiedad asegurando que pertenece al usuario autenticado
        $property = Property::where('usuario_id', Auth::id())->findOrFail($id);
        
        // 2. Validación de datos (similares al store)
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
            'certificado_energetico_archivo' => 'nullable|file|mimes:pdf|max:5120'
        ]);

        // 3. Actualización de datos básicos
        $property->fill($validated);
        
        // 4. Actualización de campos booleanos
        $booleanFields = ['tiene_ascensor', 'tiene_parking', 'tiene_terraza', 'tiene_jardin', 'tiene_piscina', 'aire_acondicionado'];
        foreach ($booleanFields as $field) {
            $property->$field = $request->has($field) ? \DB::raw('true') : \DB::raw('false');
        }

        // 5. Gestión del Certificado Energético (Reemplazo)
        if ($request->hasFile('certificado_energetico_archivo')) {
            // Eliminar archivo antiguo si existe
            if ($property->certificado_energetico_archivo) {
                Storage::disk('public')->delete($property->certificado_energetico_archivo);
            }
            $property->certificado_energetico_archivo = $request->file('certificado_energetico_archivo')->store('certificates', 'public');
        }

        $property->save();

        // 6. Gestión de nuevas imágenes (si se añaden en la edición)
        if ($request->hasFile('new_images')) {
            $this->uploadPropertyImages($property, $request->file('new_images'));
        }

        return redirect()->route('user.properties.index')->with('success', 'Propiedad actualizada correctamente.');
    }

    public function setCover($id)
    {
        $media = PropertyMedia::findOrFail($id);
        $property = Property::where('usuario_id', Auth::id())->findOrFail($media->propiedad_id);

        PropertyMedia::where('propiedad_id', $property->id)
            ->where('tipo_archivo', 'imagen')
            ->update(['es_portada' => \DB::raw('false')]);
            
        $media->update(['es_portada' => \DB::raw('true')]);
        
        return response()->json(['success' => true]);
    }

    public function deleteMedia($id)
    {
        $media = PropertyMedia::findOrFail($id);
        $property = Property::where('usuario_id', Auth::id())->findOrFail($media->propiedad_id);

        Storage::disk('public')->delete($media->ruta_archivo);
        $media->delete();

        return response()->json(['success' => true]);
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
