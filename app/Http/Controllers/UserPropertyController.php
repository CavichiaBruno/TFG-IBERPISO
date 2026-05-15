<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\PropertyMedia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Controlador que permite a los usuarios registrados gestionar sus propiedades.
 *
 * Cubre el ciclo completo de una publicación: crear, editar, pausar (activar/desactivar),
 * eliminar y gestionar las imágenes. También permite al usuario ver las consultas
 * de contacto recibidas en sus anuncios.
 */
class UserPropertyController extends Controller
{
    /**
     * Muestra el formulario para publicar una nueva propiedad.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('pages.properties.create');
    }

    /**
     * Procesa y guarda una nueva propiedad publicada por el usuario.
     *
     * Pasos que realiza:
     * 1. Valida todos los campos del formulario.
     * 2. Genera un slug único a partir del título.
     * 3. Crea el registro de la propiedad vinculado al usuario autenticado.
     * 4. Procesa los campos booleanos (checkboxes de características).
     * 5. Guarda el certificado energético (PDF) si se adjuntó.
     * 6. Sube y registra las imágenes de la galería.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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
     * Sube y registra las imágenes de la galería de una propiedad.
     *
     * La primera imagen subida se marca automáticamente como portada (es_portada = true).
     * Cada imagen se guarda físicamente en storage/app/public/properties y se crea
     * un registro en la tabla medios_propiedades.
     *
     * @param  \App\Models\Property $property La propiedad a la que pertenecen las imágenes
     * @param  array $images Array de archivos de imagen subidos
     * @return void
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

    /**
     * Muestra el listado de propiedades publicadas por el usuario autenticado.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $properties = Property::where('usuario_id', Auth::id())
            ->with(['coverImage'])
            ->select('id', 'titulo', 'precio', 'ciudad', 'activa', 'slug', 'created_at')
            ->latest()
            ->paginate(12);

        return view('pages.properties.user_index', compact('properties'));
    }

    /**
     * Muestra las consultas de contacto recibidas en las propiedades del usuario.
     *
     * @return \Illuminate\View\View
     */
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

    /**
     * Marca una consulta como leída.
     *
     * Solo permite marcar consultas recibidas en propiedades del usuario autenticado.
     *
     * @param  int $id ID de la consulta
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead($id)
    {
        $inquiry = Auth::user()->receivedInquiries()->findOrFail($id);
        $inquiry->update(['leida' => \DB::raw('true')]);

        return response()->json(['success' => true]);
    }

    /**
     * Muestra el formulario de edición de una propiedad del usuario.
     *
     * Verifica que la propiedad pertenezca al usuario autenticado antes de mostrarla.
     *
     * @param  int $id ID de la propiedad
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $property = Property::where('usuario_id', Auth::id())->with('medios')->findOrFail($id);
        return view('pages.properties.edit', compact('property'));
    }

    /**
     * Guarda los cambios realizados en una propiedad existente del usuario.
     *
     * También gestiona el reemplazo del certificado energético si se sube uno nuevo,
     * y permite añadir más imágenes a la galería.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id ID de la propiedad a actualizar
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Establece una imagen concreta como portada de la propiedad.
     *
     * Primero quita la portada de todas las imágenes de esa propiedad
     * y luego marca la seleccionada como nueva portada.
     *
     * @param  int $id ID del archivo de imagen (PropertyMedia)
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Elimina un archivo multimedia de la propiedad.
     *
     * Borra tanto el archivo físico del disco como el registro en la base de datos.
     *
     * @param  int $id ID del archivo multimedia (PropertyMedia)
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMedia($id)
    {
        $media = PropertyMedia::findOrFail($id);
        $property = Property::where('usuario_id', Auth::id())->findOrFail($media->propiedad_id);

        Storage::disk('public')->delete($media->ruta_archivo);
        $media->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Activa o desactiva la visibilidad pública de una propiedad.
     *
     * Una propiedad desactivada no aparece en las búsquedas ni en el listado público.
     *
     * @param  int $id ID de la propiedad
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleActive($id)
    {
        $property = Property::where('usuario_id', Auth::id())->findOrFail($id);
        $property->activa = $property->activa ? \DB::raw('false') : \DB::raw('true');
        $property->save();

        return back()->with('success', $property->activa ? 'Anuncio activado.' : 'Anuncio desactivado.');
    }

    /**
     * Elimina (borrado lógico) una propiedad del usuario.
     *
     * Usa SoftDeletes, por lo que el registro no se borra físicamente
     * de la base de datos, solo se marca como eliminado.
     *
     * @param  int $id ID de la propiedad
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $property = Property::where('usuario_id', Auth::id())->findOrFail($id);
        $property->delete();

        return redirect()->route('user.properties.index')->with('success', 'Anuncio eliminado correctamente.');
    }
}
