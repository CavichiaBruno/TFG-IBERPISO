<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Modelo principal que representa una Propiedad Inmobiliaria.
 *
 * Gestiona todos los datos de un inmueble: precio, ubicación, características,
 * imágenes, tipo de operación (venta/alquiler) y estado de publicación.
 *
 * Usa SoftDeletes: las propiedades eliminadas no se borran físicamente de la base
 * de datos, sino que se marcan con una fecha en "deleted_at". Esto permite
 * recuperarlas si fuese necesario.
 *
 * @property int         $id
 * @property int         $usuario_id                  ID del usuario propietario del anuncio
 * @property string      $titulo                      Título del anuncio
 * @property string      $descripcion                 Descripción detallada de la propiedad
 * @property float       $precio                      Precio de venta o alquiler mensual
 * @property float       $superficie_m2               Superficie en metros cuadrados
 * @property int         $habitaciones                Número de habitaciones
 * @property int         $banos                       Número de baños
 * @property string|null $piso                        Planta del inmueble (ej: "3ª", "Bajo")
 * @property string      $tipo_propiedad              Tipo: "piso", "casa", "chalet", "local", etc.
 * @property string      $tipo_operacion              Operación: "venta" o "alquiler"
 * @property string      $direccion                   Dirección completa
 * @property string      $ciudad                      Ciudad donde se ubica
 * @property string      $provincia                   Provincia donde se ubica
 * @property string      $codigo_postal               Código postal (5 dígitos)
 * @property float|null  $latitud                     Coordenada de latitud para el mapa
 * @property float|null  $longitud                    Coordenada de longitud para el mapa
 * @property bool        $tiene_ascensor              Dispone de ascensor
 * @property bool        $tiene_parking               Dispone de parking
 * @property bool        $tiene_terraza               Dispone de terraza
 * @property bool        $tiene_jardin                Dispone de jardín
 * @property bool        $tiene_piscina               Dispone de piscina
 * @property bool        $aire_acondicionado          Dispone de aire acondicionado
 * @property bool        $destacada                   Indica si el anuncio aparece en portada
 * @property bool        $activa                      Indica si el anuncio está visible públicamente
 * @property string|null $certificado_energetico      Calificación energética: A, B, C, D, E, F o G
 * @property string|null $certificado_energetico_archivo Ruta del PDF del certificado energético
 * @property string|null $url_tour_virtual            URL del tour virtual 360°
 * @property string      $slug                        URL amigable generada a partir del título
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

    /**
     * Devuelve el usuario propietario del anuncio.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario() { return $this->belongsTo(User::class, 'usuario_id'); }

    /**
     * Devuelve todos los archivos multimedia de la propiedad, ordenados por posición.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medios() { return $this->hasMany(PropertyMedia::class, 'propiedad_id')->orderBy('orden'); }

    /**
     * Devuelve el archivo de imagen marcado como portada.
     * Si no hay ninguna marcada, devuelve la primera imagen disponible.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function coverImage()
    {
        return $this->hasOne(PropertyMedia::class, 'propiedad_id')
            ->where('tipo_archivo', 'imagen')
            ->orderBy('es_portada', 'desc')
            ->orderBy('orden', 'asc');
    }

    /**
     * Alias de medios() para compatibilidad con código anterior.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function media() { return $this->medios(); }

    /**
     * Devuelve solo los archivos de tipo imagen, ordenados por posición.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function imagenes() { return $this->hasMany(PropertyMedia::class, 'propiedad_id')->where('tipo_archivo', 'imagen')->orderBy('orden'); }

    /**
     * Devuelve todas las consultas de contacto recibidas sobre esta propiedad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function consultas() { return $this->hasMany(Inquiry::class, 'propiedad_id'); }

    /**
     * Devuelve todas las interacciones (likes/dislikes) que ha recibido esta propiedad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function interacciones() { return $this->hasMany(PropertyInteraction::class, 'propiedad_id'); }

    // --- Scopes (Atajos para consultas comunes) ---

    /**
     * Scope para filtrar solo las propiedades activas (visibles al público).
     *
     * Uso: Property::active()->get()
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query) { return $query->where('activa', \DB::raw('true')); }

    /**
     * Scope para filtrar solo las propiedades marcadas como destacadas.
     *
     * Uso: Property::featured()->get()
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFeatured($query) { return $query->where('destacada', \DB::raw('true')); }

    /**
     * Scope para filtrar propiedades por tipo de operación (venta o alquiler).
     *
     * Uso: Property::filterByOperation('venta')->get()
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $op Tipo de operación: "venta" o "alquiler"
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByOperation($query, string $op) { return $query->where('tipo_operacion', $op); }

    // --- Accessors (Campos calculados o formateados) ---

    /**
     * Genera el slug amigable para URLs basándose en el título.
     * Ej: "Piso en Madrid Centro" → "piso-en-madrid-centro"
     *
     * @return string
     */
    public function getSlugAttribute(): string { return Str::slug($this->titulo); }

    /**
     * Formatea el precio con separador de miles.
     * Ej: 125000 → "125.000"
     *
     * @return string
     */
    public function getFormattedPriceAttribute(): string { return number_format((float) $this->precio, 0, ',', '.'); }

    /**
     * Obtiene la URL de la imagen de portada con lógica de fallback (respaldo).
     *
     * Sigue este orden de prioridad para evitar consultas innecesarias a la base de datos:
     * 1. Usa la relación coverImage si ya está cargada en memoria (Eager Loading).
     * 2. Busca en la colección de medios si ya está cargada en memoria.
     * 3. Lanza una consulta directa a la base de datos (Lazy Loading).
     * Si no hay ninguna imagen, devuelve una imagen de Unsplash por defecto.
     *
     * @return string|null URL de la imagen de portada o imagen por defecto.
     */
    public function getCoverUrlAttribute(): ?string
    {
        try {
            // Prioridad 1: Si ya cargamos la relación coverImage mediante Eager Loading, la usamos
            if ($this->relationLoaded('coverImage') && $this->coverImage) {
                return $this->coverImage->url;
            }

            // Prioridad 2: Buscar en la colección de medios si ya está cargada (evita queries extra)
            if ($this->relationLoaded('medios')) {
                $cover = $this->medios->where('tipo_archivo', 'imagen')->where('es_portada', true)->first()
                    ?? $this->medios->where('tipo_archivo', 'imagen')->first();
                return $cover ? $cover->url : 'https://images.unsplash.com/photo-1560185127-6ed189bf02f4?auto=format&fit=crop&q=80&w=1200';
            }

            // Prioridad 3: Si nada está cargado, buscamos directamente el cover (Lazy Loading)
            $cover = $this->coverImage()->first();
            return $cover ? $cover->url : 'https://images.unsplash.com/photo-1560185127-6ed189bf02f4?auto=format&fit=crop&q=80&w=1200';
        } catch (\Exception $e) {
            // Si hay cualquier error, devolvemos una imagen por defecto para no romper la UI
            return 'https://images.unsplash.com/photo-1560185127-6ed189bf02f4?auto=format&fit=crop&q=80&w=1200';
        }
    }

    // Listados de opciones fijas para el sistema

    /**
     * Devuelve los tipos de propiedad disponibles en la plataforma.
     *
     * @return string[]
     */
    public static function getPropertyTypes(): array { return ['piso', 'casa', 'chalet', 'local', 'garaje', 'oficina']; }

    /**
     * Devuelve los tipos de operación disponibles.
     *
     * @return string[]
     */
    public static function getOperationTypes(): array { return ['venta', 'alquiler']; }

    /**
     * Devuelve las calificaciones de certificado energético válidas (de mayor a menor eficiencia).
     *
     * @return string[]
     */
    public static function getEnergyCertificates(): array { return ['A', 'B', 'C', 'D', 'E', 'F', 'G']; }
}
