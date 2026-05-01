<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Modelo que representa una Propiedad Inmobiliaria.
 */
class Property extends Model
{
    use SoftDeletes; // Permite el borrado lógico (quedan en BD pero no se muestran)

    protected $fillable = [
        'user_id', 'title', 'description', 'price', 'surface_m2', 'rooms', 
        'bathrooms', 'floor', 'property_type', 'operation_type', 'address', 
        'city', 'province', 'postal_code', 'latitude', 'longitude', 
        'has_elevator', 'has_parking', 'has_terrace', 'has_garden', 
        'has_pool', 'air_conditioning', 'is_featured', 'is_active', 
        'energy_certificate', 'virtual_tour_url',
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

    // --- Relaciones ---

    public function user() { return $this->belongsTo(User::class); }
    public function media() { return $this->hasMany(PropertyMedia::class)->orderBy('sort_order'); }
    public function images() { return $this->hasMany(PropertyMedia::class)->where('file_type', 'image')->orderBy('sort_order'); }
    public function inquiries() { return $this->hasMany(Inquiry::class); }
    public function interactions() { return $this->hasMany(PropertyInteraction::class); }

    // --- Scopes (Atajos para consultas comunes) ---

    public function scopeActive($query) { return $query->where('is_active', true); }
    public function scopeFeatured($query) { return $query->where('is_featured', true); }
    public function scopeFilterByOperation($query, string $op) { return $query->where('operation_type', $op); }

    // --- Accessors (Campos calculados o formateados) ---

    // Genera el slug amigable para URLs basándose en el título
    public function getSlugAttribute(): string { return Str::slug($this->title); }

    // Formatea el precio con separador de miles
    public function getFormattedPriceAttribute(): string { return number_format((float) $this->price, 0, ',', '.'); }

    // Obtiene la URL de la imagen de portada o un placeholder si no hay
    public function getCoverUrlAttribute(): ?string
    {
        $cover = $this->media->where('file_type', 'image')->where('is_cover', true)->first()
            ?? $this->media->where('file_type', 'image')->first();
        
        return $cover ? $cover->url : asset('images/placeholder.jpg');
    }

    // Listados de opciones fijas para el sistema
    public static function getPropertyTypes(): array { return ['piso', 'casa', 'chalet', 'local', 'garaje', 'oficina']; }
    public static function getOperationTypes(): array { return ['venta', 'alquiler']; }
    public static function getEnergyCertificates(): array { return ['A', 'B', 'C', 'D', 'E', 'F', 'G']; }
}
