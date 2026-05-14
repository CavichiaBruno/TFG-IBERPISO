<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Modelo que representa una Propiedad Inmobiliaria.
 */
class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'propiedades';

    protected $fillable = [
        'usuario_id', 'titulo', 'descripcion', 'precio', 'superficie_m2', 'habitaciones', 
        'banos', 'piso', 'tipo_propiedad', 'tipo_operacion', 'direccion', 
        'ciudad', 'provincia', 'codigo_postal', 'latitud', 'longitud', 
        'tiene_ascensor', 'tiene_parking', 'tiene_terraza', 'tiene_jardin', 
        'tiene_piscina', 'aire_acondicionado', 'destacada', 'activa', 
        'certificado_energetico', 'url_tour_virtual', 'slug', 'certificado_energetico_archivo',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'superficie_m2' => 'decimal:2',
        'latitud' => 'decimal:7',
        'longitud' => 'decimal:7',
        'tiene_ascensor' => 'boolean',
        'tiene_parking' => 'boolean',
        'tiene_terraza' => 'boolean',
        'tiene_jardin' => 'boolean',
        'tiene_piscina' => 'boolean',
        'aire_acondicionado' => 'boolean',
        'destacada' => 'boolean',
        'activa' => 'boolean',
    ];

    // --- Relaciones ---

    public function usuario() { return $this->belongsTo(User::class, 'usuario_id'); }
    public function medios() { return $this->hasMany(PropertyMedia::class, 'propiedad_id')->orderBy('orden'); }
    public function media() { return $this->medios(); } // Alias para compatibilidad
    public function imagenes() { return $this->hasMany(PropertyMedia::class, 'propiedad_id')->where('tipo_archivo', 'imagen')->orderBy('orden'); }
    public function consultas() { return $this->hasMany(Inquiry::class, 'propiedad_id'); }
    public function interacciones() { return $this->hasMany(PropertyInteraction::class, 'propiedad_id'); }

    // --- Scopes (Atajos para consultas comunes) ---

    public function scopeActive($query) { return $query->where('activa', \DB::raw('true')); }
    public function scopeFeatured($query) { return $query->where('destacada', \DB::raw('true')); }
    public function scopeFilterByOperation($query, string $op) { return $query->where('tipo_operacion', $op); }

    // --- Accessors (Campos calculados o formateados) ---

    // Genera el slug amigable para URLs basándose en el título
    public function getSlugAttribute(): string { return Str::slug($this->titulo); }

    // Formatea el precio con separador de miles
    public function getFormattedPriceAttribute(): string { return number_format((float) $this->precio, 0, ',', '.'); }

    // Obtiene la URL de la imagen de portada o un placeholder si no hay
    public function getCoverUrlAttribute(): ?string
    {
        try {
            // Obtener medios (eagerly loaded si está disponible, sino carga lazy)
            $medios = $this->relationLoaded('medios') ? $this->medios : $this->medios()->get();
            
            // Buscar primera imagen que sea portada, sino la primera imagen
            $cover = $medios->where('tipo_archivo', 'imagen')->where('es_portada', true)->first()
                ?? $medios->where('tipo_archivo', 'imagen')->first();
            
            return $cover ? $cover->url : 'https://images.unsplash.com/photo-1560185127-6ed189bf02f4?auto=format&fit=crop&q=80&w=1200';
        } catch (\Exception $e) {
            return 'https://images.unsplash.com/photo-1560185127-6ed189bf02f4?auto=format&fit=crop&q=80&w=1200';
        }
    }

    // Listados de opciones fijas para el sistema
    public static function getPropertyTypes(): array { return ['piso', 'casa', 'chalet', 'local', 'garaje', 'oficina']; }
    public static function getOperationTypes(): array { return ['venta', 'alquiler']; }
    public static function getEnergyCertificates(): array { return ['A', 'B', 'C', 'D', 'E', 'F', 'G']; }
}
