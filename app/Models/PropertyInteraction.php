<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que registra las interacciones de los usuarios con las propiedades.
 *
 * Guarda si un usuario ha dado "like" (le gusta) o "dislike" (no le gusta)
 * a una propiedad dentro de la sección IberScroll (descubrimiento tipo swipe).
 *
 * @property int    $id
 * @property int    $usuario_id   ID del usuario que realizó la interacción
 * @property int    $propiedad_id ID de la propiedad con la que se interactuó
 * @property string $tipo         Tipo de interacción: "like" o "dislike"
 */
class PropertyInteraction extends Model
{
    protected $table = 'interacciones_propiedades';

    protected $fillable = [
        'usuario_id',
        'propiedad_id',
        'tipo',
    ];

    /**
     * Devuelve el usuario que realizó la interacción.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Devuelve la propiedad sobre la que se realizó la interacción.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property()
    {
        return $this->belongsTo(Property::class, 'propiedad_id');
    }
}
