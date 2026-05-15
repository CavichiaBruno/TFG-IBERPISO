<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modelo que representa un Usuario registrado en IberPiso.
 *
 * Un usuario puede tener el rol "usuario" (comprador/vendedor) o "admin".
 * Extiende Authenticatable para integrarse con el sistema de login de Laravel,
 * lo que permite usar Auth::login(), Auth::user(), etc.
 *
 * @property int         $id
 * @property string      $nombre    Nombre completo del usuario
 * @property string      $correo    Dirección de correo electrónico (único)
 * @property string      $contrasena Contraseña cifrada con bcrypt
 * @property string      $rol       Rol del usuario: "usuario" o "admin"
 * @property string|null $telefono  Número de teléfono de contacto
 * @property string|null $avatar    Ruta del avatar/foto de perfil
 * @property bool        $activo    Indica si la cuenta está habilitada para acceder
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'correo',
        'contrasena',
        'rol',
        'telefono',
        'avatar',
        'activo',
    ];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'correo_verificado_en' => 'datetime',
            'contrasena' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    /**
     * Indica si el usuario tiene rol de administrador.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    /**
     * Indica a Laravel qué campo usar como contraseña para el login.
     * Necesario porque usamos "contrasena" en lugar del nombre estándar "password".
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    /**
     * Devuelve todas las propiedades publicadas por este usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany(Property::class, 'usuario_id');
    }

    /**
     * Devuelve todas las consultas de contacto enviadas por este usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inquiries()
    {
        return $this->hasMany(Inquiry::class, 'usuario_id');
    }

    /**
     * Devuelve todas las interacciones (likes/dislikes) realizadas por este usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function interactions()
    {
        return $this->hasMany(PropertyInteraction::class, 'usuario_id');
    }

    /**
     * Devuelve las propiedades que el usuario ha marcado con "like" (favoritos).
     *
     * Usa la tabla pivote "interacciones_propiedades" filtrando solo los registros
     * de tipo "like".
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoriteProperties()
    {
        return $this->belongsToMany(Property::class, 'interacciones_propiedades')
            ->wherePivot('tipo', '=', 'like')
            ->withTimestamps();
    }

    /**
     * Devuelve todas las consultas recibidas en las propiedades de este usuario.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function receivedInquiries()
    {
        return Inquiry::whereIn('propiedad_id', $this->properties()->pluck('id'));
    }

    /**
     * Devuelve el número de consultas no leídas recibidas en las propiedades del usuario.
     * Se usa para mostrar el contador de notificaciones en el menú.
     *
     * @return int
     */
    public function getUnreadInquiriesCountAttribute(): int
    {
        return $this->receivedInquiries()->where('leida', \DB::raw('false'))->count();
    }
}
