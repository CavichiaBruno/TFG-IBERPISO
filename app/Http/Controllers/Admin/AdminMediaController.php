<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controlador que gestiona la subida y eliminación de archivos multimedia en el panel admin.
 *
 * Permite al administrador añadir imágenes, PDFs y vídeos a las propiedades,
 * eliminar archivos existentes y establecer cuál es la imagen de portada.
 */
class AdminMediaController extends Controller
{
    /**
     * Sube un nuevo archivo multimedia y lo asocia a una propiedad.
     *
     * Valida el tipo MIME en el servidor para mayor seguridad.
     * Si es la primera imagen de la propiedad, se marca automáticamente como portada.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id ID de la propiedad a la que se asocia el archivo
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, int $id)
    {
        $property = Property::findOrFail($id);

        $request->validate([
            'file'         => 'required|file|max:102400',
            'tipo_archivo' => 'required|in:imagen,pdf,video',
        ]);

        $file     = $request->file('file');
        $fileType = $request->tipo_archivo;

        // Validar tipos MIME en el servidor
        $allowedMimes = [
            'imagen' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
            'pdf'    => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'video'  => ['video/mp4', 'video/quicktime', 'video/x-msvideo'],
        ];

        if (!in_array($file->getMimeType(), $allowedMimes[$fileType])) {
            return response()->json(['error' => 'Tipo de archivo no permitido.'], 422);
        }

        $directory = match ($fileType) {
            'imagen' => 'properties',
            'pdf'    => 'documents',
            'video'  => 'videos',
            default  => 'uploads',
        };

        $path = $file->store($directory, 'public');

        $isCover = $fileType === 'imagen' && !$property->medios()->where('tipo_archivo', 'imagen')->exists();

        $media = PropertyMedia::create([
            'propiedad_id'     => $property->id,
            'ruta_archivo'     => $path,
            'tipo_archivo'     => $fileType,
            'tipo_mime'        => $file->getMimeType(),
            'tamano_archivo_kb' => (int) ceil($file->getSize() / 1024),
            'nombre_original'  => $file->getClientOriginalName(),
            'es_portada'       => $isCover ? \DB::raw('true') : \DB::raw('false'),
            'orden'            => $property->medios()->count(),
        ]);

        return response()->json([
            'success' => true,
            'media'   => [
                'id'             => $media->id,
                'url'            => $media->url,
                'tipo_archivo'   => $media->tipo_archivo,
                'nombre_original' => $media->nombre_original,
                'es_portada'     => $media->es_portada,
            ],
        ]);
    }

    /**
     * Elimina un archivo multimedia y su registro en la base de datos.
     *
     * Si el archivo está almacenado localmente (no es una URL externa ni data URI),
     * también se borra el archivo físico del disco.
     *
     * @param  int $id ID del archivo multimedia (PropertyMedia)
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $media = PropertyMedia::findOrFail($id);

        if (!str_starts_with($media->ruta_archivo, 'data:') && !str_starts_with($media->ruta_archivo, 'http')) {
            Storage::disk('public')->delete($media->ruta_archivo);
        }

        $media->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Establece un archivo de imagen como la portada de su propiedad.
     *
     * Primero elimina la portada actual de todas las imágenes de esa propiedad
     * y luego marca la seleccionada como la nueva portada.
     *
     * @param  int $id ID del archivo de imagen (PropertyMedia)
     * @return \Illuminate\Http\JsonResponse
     */
    public function setCover(int $id)
    {
        $media = PropertyMedia::findOrFail($id);

        // Quitar la portada de las otras imágenes de la misma propiedad
        PropertyMedia::where('propiedad_id', $media->propiedad_id)
            ->where('tipo_archivo', 'imagen')
            ->update(['es_portada' => \DB::raw('false')]);

        $media->update(['es_portada' => \DB::raw('true')]);

        return response()->json(['success' => true]);
    }
}
