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
            'file_type' => 'required|in:image,pdf,video',
        ]);

        $file     = $request->file('file');
        $fileType = $request->file_type;

        // Validate MIME types server-side
        $allowedMimes = [
            'image' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
            'pdf'   => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'video' => ['video/mp4', 'video/quicktime', 'video/x-msvideo'],
        ];

        if (!in_array($file->getMimeType(), $allowedMimes[$fileType])) {
            return response()->json(['error' => 'Tipo de archivo no permitido.'], 422);
        }

        // Instead of storing in disk, store as base64
        $base64 = base64_encode(file_get_contents($file->getRealPath()));
        $path = 'data:' . $file->getMimeType() . ';base64,' . $base64;

        $isCover = $fileType === 'image' && !$property->media()->where('file_type', 'image')->exists();

        $media = PropertyMedia::create([
            'property_id'   => $property->id,
            'file_path'     => $path,
            'file_type'     => $fileType,
            'mime_type'     => $file->getMimeType(),
            'file_size_kb'  => (int) ceil($file->getSize() / 1024),
            'original_name' => $file->getClientOriginalName(),
            'is_cover'      => $isCover,
            'sort_order'    => $property->media()->count(),
        ]);

        return response()->json([
            'success' => true,
            'media'   => [
                'id'       => $media->id,
                'url'      => $media->url,
                'type'     => $media->file_type,
                'name'     => $media->original_name,
                'is_cover' => $media->is_cover,
            ],
        ]);
    }

    public function destroy(int $id)
    {
        $media = PropertyMedia::findOrFail($id);
        // We don't need to delete from disk since it's base64 in DB
        // Storage::disk('public')->delete($media->file_path);
        $media->delete();
        return response()->json(['success' => true]);
    }

    public function setCover(int $id)
    {
        $media = PropertyMedia::findOrFail($id);

        // Remove cover from other images of the same property
        PropertyMedia::where('property_id', $media->property_id)
            ->where('file_type', 'image')
            ->update(['is_cover' => false]);

        $media->update(['is_cover' => true]);

        return response()->json(['success' => true]);
    }
}
