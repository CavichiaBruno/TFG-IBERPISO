<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

    public function isAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'usuario_id');
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class, 'usuario_id');
    }

    public function interactions()
    {
        return $this->hasMany(PropertyInteraction::class, 'usuario_id');
    }

    public function favoriteProperties()
    {
        return $this->belongsToMany(Property::class, 'interacciones_propiedades')
            ->wherePivot('tipo', '=', 'like')
            ->withTimestamps();
    }
}
