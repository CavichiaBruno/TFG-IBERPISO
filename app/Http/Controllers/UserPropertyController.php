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
            'title' => 'required|string|max:200',
            'description' => 'required|string|min:100',
            'property_type' => 'required|string',
            'operation_type' => 'required|in:venta,alquiler',
            'price' => 'required|numeric|min:0',
            'surface_m2' => 'required|numeric|min:0',
            'rooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'address' => 'required|string',
            'city' => 'required|string',
            'province' => 'required|string',
            'postal_code' => 'required|string|size:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120'
        ]);

        // Auto-generate slug
        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Property::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $property = new Property();
        $property->fill($validated);
        $property->slug = $slug;
        $property->user_id = Auth::id(); // Assign to the logged in user
        // Users properties are active by default as requested
        $property->is_active = true; 
        
        // Default boolean fields to 0 if not present
        $booleanFields = ['is_featured', 'has_elevator', 'has_parking', 'has_terrace', 'has_garden', 'has_pool', 'air_conditioning'];
        foreach ($booleanFields as $field) {
            $property->$field = $request->has($field);
        }

        $property->save();

        // Handle Image Uploads
        if ($request->hasFile('images')) {
            $isFirst = true;
            foreach ($request->file('images') as $image) {
                $mimeType = $image->getMimeType();
                $base64 = base64_encode(file_get_contents($image->getRealPath()));
                $path = 'data:' . $mimeType . ';base64,' . $base64;

                PropertyMedia::create([
                    'property_id' => $property->id,
                    'file_path' => $path,
                    'file_type' => 'image',
                    'mime_type' => $mimeType,
                    'file_size_kb' => (int) ceil($image->getSize() / 1024),
                    'original_name' => $image->getClientOriginalName(),
                    'is_cover' => $isFirst,
                ]);

                $isFirst = false;
            }
        }

        return redirect()->route('properties.show', [$property->id, $property->slug])
                         ->with('success', '¡Publicación creada exitosamente!');
    }
    public function index()
    {
        $properties = Property::where('user_id', Auth::id())
            ->with(['media' => function($query) {
                $query->where('file_type', 'image')->orderBy('is_cover', 'desc');
            }])
            ->latest()
            ->get();

        return view('pages.properties.user_index', compact('properties'));
    }

    public function toggleActive($id)
    {
        $property = Property::where('user_id', Auth::id())->findOrFail($id);
        $property->is_active = !$property->is_active;
        $property->save();

        return back()->with('success', $property->is_active ? 'Anuncio activado.' : 'Anuncio desactivado.');
    }

    public function destroy($id)
    {
        $property = Property::where('user_id', Auth::id())->findOrFail($id);
        $property->delete();

        return redirect()->route('user.properties.index')->with('success', 'Anuncio eliminado correctamente.');
    }
}
