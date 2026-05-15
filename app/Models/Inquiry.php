<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa una Consulta de contacto sobre una propiedad.
 *
 * Cuando un usuario (o un visitante anónimo) envía el formulario de contacto
 * desde la ficha de una propiedad, se crea un registro de consulta.
 * El propietario puede ver y gestionar estas consultas desde su panel.
 *
 * @property int         $id
 * @property int|null    $propiedad_id       ID de la propiedad consultada
 * @property int|null    $usuario_id         ID del usuario que envió la consulta (null si es anónimo)
 * @property string|null $nombre_visitante   Nombre del visitante anónimo
 * @property string|null $correo_visitante   Correo del visitante anónimo
 * @property string|null $telefono_visitante Teléfono del visitante anónimo
 * @property string      $mensaje            Contenido del mensaje enviado
 * @property string      $estado             Estado: "pendiente", "leida" o "respondida"
 * @property bool        $leida              Indica si la consulta ya fue leída por el propietario
 */
class Inquiry extends Model
{
    protected $table = 'consultas';

    protected $fillable = [
        'propiedad_id', 'usuario_id', 'nombre_visitante', 'correo_visitante',
        'telefono_visitante', 'mensaje', 'estado', 'leida',
    ];

    protected $casts = [
        'leida' => 'boolean',
    ];

    /**
     * Devuelve la propiedad a la que pertenece esta consulta.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property()
    {
        return $this->belongsTo(Property::class, 'propiedad_id');
    }

    /**
     * Alias en español de la relación con la propiedad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function propiedad()
    {
        return $this->property();
    }

    /**
     * Devuelve el usuario registrado que realizó la consulta (puede ser null si fue anónimo).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Alias en español de la relación con el usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->user();
    }

    /**
     * Devuelve el nombre del remitente.
     *
     * Si el remitente tiene cuenta, devuelve su nombre registrado.
     * Si es un visitante anónimo, devuelve el nombre que escribió en el formulario.
     *
     * @return string
     */
    public function getSenderNameAttribute(): string
    {
        return $this->user ? $this->user->nombre : ($this->nombre_visitante ?? 'Desconocido');
    }

    /**
     * Devuelve el correo del remitente.
     *
     * Si el remitente tiene cuenta, devuelve su correo registrado.
     * Si es un visitante anónimo, devuelve el correo que escribió en el formulario.
     *
     * @return string|null
     */
    public function getSenderEmailAttribute(): ?string
    {
        return $this->user ? $this->user->correo : $this->correo_visitante;
    }

    /**
     * Scope para filtrar solo las consultas con estado "pendiente".
     *
     * Uso: Inquiry::pending()->get()
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para filtrar solo las consultas no leídas.
     *
     * Uso: Inquiry::unread()->count()
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnread($query)
    {
        return $query->where('leida', \DB::raw('false'));
    }
}
