<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Modelo que representa una Propiedad Inmobiliaria en el sistema.
 * Un inmueble tiene diversos atributos (precio, superficie, etc.) 
 * y puede tener varias imágenes asociadas.
 */
class Property extends Model
{
    use SoftDeletes; // Permite el borrado lógico (no elimina de la BD inmediatamente)

    // Campos que se pueden rellenar de forma masiva
    protected $fillable = [
        'user_id',          // ID del usuario/agente que creó el anuncio
        'title',            // Título descriptivo (ej: Piso céntrico)
        'description',      // Descripción detallada
        'price',            // Precio en euros
        'surface_m2',       // Metros cuadrados útiles
        'rooms',            // Número de habitaciones
        'bathrooms',        // Número de baños
        'floor',            // Planta (ej: 1º, Bajo, Ático)
        'property_type',    // Tipo: piso, casa, local, etc.
        'operation_type',   // Operación: venta o alquiler
        'address',          // Dirección completa
        'city',             // Localidad
        'province',         // Provincia
        'postal_code',      // Código Postal
        'latitude',         // Coordenada para el mapa
        'longitude',        // Coordenada para el mapa
        'has_elevator',     // ¿Tiene ascensor?
        'has_parking',      // ¿Tiene plaza de garaje?
        'has_terrace',      // ¿Tiene terraza?
        'has_garden',       // ¿Tiene jardín?
        'has_pool',         // ¿Tiene piscina?
        'air_conditioning', // ¿Tiene aire acondicionado?
        'is_featured',      // Si aparece en la sección de destacados
        'is_active',        // Si el anuncio está visible públicamente
        'energy_certificate', // Calificación energética (A, B, C...)
        'virtual_tour_url',   // Enlace opcional a tour 360
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'surface_m2' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'has_elevator' => 'boolean',
        'has_parking' => 'boolean',
        'has_terrace' => 'boolean',
        'has_garden' => 'boolean',
        'has_pool' => 'boolean',
        'air_conditioning' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    // --- Relationships ---

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->hasMany(PropertyMedia::class)->orderBy('sort_order');
    }

    public function images()
    {
        return $this->hasMany(PropertyMedia::class)->where('file_type', 'image')->orderBy('sort_order');
    }

    public function documents()
    {
        return $this->hasMany(PropertyMedia::class)->where('file_type', 'pdf')->orderBy('sort_order');
    }

    public function coverImage()
    {
        return $this->hasOne(PropertyMedia::class)->where('file_type', 'image')->where('is_cover', true);
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }

    public function interactions()
    {
        return $this->hasMany(PropertyInteraction::class);
    }

    // --- Scopes ---

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeFilterByType($query, string $type)
    {
        return $query->where('property_type', $type);
    }

    public function scopeFilterByOperation($query, string $operation)
    {
        return $query->where('operation_type', $operation);
    }

    // --- Accessors ---

    public function getSlugAttribute(): string
    {
        return Str::slug($this->title);
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format((float) $this->price, 0, ',', '.');
    }

    public function getCoverUrlAttribute(): ?string
    {
        $cover = $this->media->where('file_type', 'image')->where('is_cover', true)->first()
            ?? $this->media->where('file_type', 'image')->first();

        return $cover ? asset('storage/' . $cover->file_path) : asset('images/placeholder.jpg');
    }

    public static function getPropertyTypes(): array
    {
        return ['piso', 'casa', 'chalet', 'local', 'garaje', 'oficina'];
    }

    public static function getOperationTypes(): array
    {
        return ['venta', 'alquiler'];
    }

    public static function getEnergyCertificates(): array
    {
        return ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
    }
}
