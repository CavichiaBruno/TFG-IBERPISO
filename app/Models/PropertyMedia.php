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

    public function getUrlAttribute(): string
    {
        if (str_starts_with($this->ruta_archivo, 'data:') || str_starts_with($this->ruta_archivo, 'http')) {
            return $this->ruta_archivo;
        }

        if (str_starts_with($this->ruta_archivo, 'images/') || str_starts_with($this->ruta_archivo, 'assets/')) {
            return '/' . $this->ruta_archivo;
        }

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
