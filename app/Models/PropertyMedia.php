<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyMedia extends Model
{
    protected $table = 'medios_propiedades';

    protected $fillable = [
        'propiedad_id', 'ruta_archivo', 'tipo_archivo', 'tipo_mime',
        'tamano_archivo_kb', 'nombre_original', 'es_portada', 'orden',
    ];

    protected $casts = [
        'es_portada'  => 'boolean',
        'orden'       => 'integer',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'propiedad_id');
    }

    public function getUrlAttribute(): ?string
    {
        // Si no hay ruta archivo, retorna null
        if (empty($this->ruta_archivo)) {
            return null;
        }

        // URLs externas y data URIs se devuelven tal cual
        if (str_starts_with($this->ruta_archivo, 'data:') || str_starts_with($this->ruta_archivo, 'http')) {
            return $this->ruta_archivo;
        }

        // Rutas que ya tienen prefijo web
        if (str_starts_with($this->ruta_archivo, 'images/') || str_starts_with($this->ruta_archivo, 'assets/')) {
            return '/' . $this->ruta_archivo;
        }

        // Rutas de storage
        return '/storage/' . $this->ruta_archivo;
    }

    public function scopeImages($query)
    {
        return $query->where('tipo_archivo', 'imagen');
    }

    public function scopeDocuments($query)
    {
        return $query->where('tipo_archivo', 'pdf');
    }

    public function scopeVideos($query)
    {
        return $query->where('tipo_archivo', 'video');
    }
}
