<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa un Artículo o Noticia del blog de IberPiso.
 *
 * Almacena las noticias y artículos informativos que se muestran
 * en la sección de noticias de la plataforma.
 *
 * @property int              $id
 * @property string           $titulo            Título del artículo
 * @property string           $slug              Identificador amigable para la URL (ej: "consejos-para-vender")
 * @property string           $contenido         Cuerpo completo del artículo
 * @property string|null      $autor             Nombre del autor (por defecto "IberPiso")
 * @property string|null      $imagen_url        URL de la imagen de cabecera
 * @property string|null      $categoria         Categoría del artículo (ej: "Noticias", "Consejos")
 * @property bool             $publicado         Indica si el artículo es visible para el público
 * @property \Carbon\Carbon|null $fecha_publicacion Fecha en que se publicó el artículo
 */
class Article extends Model
{
    use HasFactory;

    protected $table = 'articulos';

    protected $fillable = [
        'titulo', 'slug', 'contenido', 'autor', 'imagen_url',
        'categoria', 'publicado', 'fecha_publicacion'
    ];

    protected function casts(): array
    {
        return [
            'publicado' => 'boolean',
            'fecha_publicacion' => 'datetime',
        ];
    }
}
