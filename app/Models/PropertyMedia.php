<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa un archivo multimedia asociado a una propiedad.
 *
 * Puede ser una imagen (jpg, png, webp), un documento PDF (certificado energético)
 * o un vídeo. Cada propiedad puede tener varios archivos, pero solo uno
 * puede estar marcado como portada (es_portada = true).
 *
 * @property int         $id
 * @property int         $propiedad_id      ID de la propiedad a la que pertenece
 * @property string      $ruta_archivo      Ruta relativa del archivo en el almacenamiento
 * @property string      $tipo_archivo      Tipo de archivo: "imagen", "pdf" o "video"
 * @property string      $tipo_mime         Tipo MIME del archivo (ej: "image/jpeg")
 * @property int|null    $tamano_archivo_kb Tamaño del archivo en kilobytes
 * @property string|null $nombre_original   Nombre original del archivo al subirse
 * @property bool        $es_portada        Indica si esta imagen es la portada de la propiedad
 * @property int         $orden             Posición del archivo en la galería
 */
class PropertyMedia extends Model
{
    protected $table = 'medios_propiedades';

    protected $fillable = [
        'propiedad_id', 'ruta_archivo', 'tipo_archivo', 'tipo_mime',
        'tamano_archivo_kb', 'nombre_original', 'es_portada', 'orden',
    ];

    protected function casts(): array
    {
        return [
            'es_portada'  => 'boolean',
            'orden'       => 'integer',
        ];
    }

    /**
     * Devuelve la propiedad a la que pertenece este archivo multimedia.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property()
    {
        return $this->belongsTo(Property::class, 'propiedad_id');
    }

    /**
     * Genera y devuelve la URL pública del archivo.
     *
     * Gestiona tres casos:
     * - URLs externas (http) o data URIs: se devuelven tal cual.
     * - Rutas con prefijo web (images/, assets/): se añade la barra inicial.
     * - Rutas de storage de Laravel: se añade el prefijo /storage/.
     *
     * @return string|null La URL pública del archivo, o null si no hay ruta.
     */
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

    /**
     * Scope para filtrar solo los archivos de tipo imagen.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeImages($query)
    {
        return $query->where('tipo_archivo', 'imagen');
    }

    /**
     * Scope para filtrar solo los archivos de tipo PDF (documentos).
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDocuments($query)
    {
        return $query->where('tipo_archivo', 'pdf');
    }

    /**
     * Scope para filtrar solo los archivos de tipo vídeo.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVideos($query)
    {
        return $query->where('tipo_archivo', 'video');
    }
}
