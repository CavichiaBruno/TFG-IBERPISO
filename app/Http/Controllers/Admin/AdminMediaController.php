<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminMediaController extends Controller
{
    public function store(Request $request, int $id)
    {
        $property = Property::findOrFail($id);

        $request->validate([
            'file'      => 'required|file|max:102400',
            'tipo_archivo' => 'required|in:imagen,pdf,video',
        ]);

        $file     = $request->file('file');
        $fileType = $request->tipo_archivo;

        // Validar tipos MIME en el servidor
        $allowedMimes = [
            'imagen' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
            'pdf'   => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'video' => ['video/mp4', 'video/quicktime', 'video/x-msvideo'],
        ];

        if (!in_array($file->getMimeType(), $allowedMimes[$fileType])) {
            return response()->json(['error' => 'Tipo de archivo no permitido.'], 422);
        }

        // En lugar de guardar en disco, lo guardamos como base64
        $base64 = base64_encode(file_get_contents($file->getRealPath()));
        $path = 'data:' . $file->getMimeType() . ';base64,' . $base64;

        $isCover = $fileType === 'imagen' && !$property->medios()->where('tipo_archivo', 'imagen')->exists();

        $media = PropertyMedia::create([
            'propiedad_id'   => $property->id,
            'ruta_archivo'     => $path,
            'tipo_archivo'     => $fileType,
            'tipo_mime'     => $file->getMimeType(),
            'tamano_archivo_kb'  => (int) ceil($file->getSize() / 1024),
            'nombre_original' => $file->getClientOriginalName(),
            'es_portada'      => $isCover,
            'orden'    => $property->medios()->count(),
        ]);

        return response()->json([
            'success' => true,
            'media'   => [
                'id'       => $media->id,
                'url'      => $media->url,
                'tipo_archivo'     => $media->tipo_archivo,
                'nombre_original'     => $media->nombre_original,
                'es_portada' => $media->es_portada,
            ],
        ]);
    }

    public function destroy(int $id)
    {
        $media = PropertyMedia::findOrFail($id);
        // No necesitamos borrar del disco ya que es base64 en la BD
        // Storage::disk('public')->delete($media->file_path);
        $media->delete();
        return response()->json(['success' => true]);
    }

    public function setCover(int $id)
    {
        $media = PropertyMedia::findOrFail($id);

        // Quitar la portada de las otras imágenes de la misma propiedad
        PropertyMedia::where('propiedad_id', $media->propiedad_id)
            ->where('tipo_archivo', 'imagen')
            ->update(['es_portada' => false]);

        $media->update(['es_portada' => true]);

        return response()->json(['success' => true]);
    }
}
