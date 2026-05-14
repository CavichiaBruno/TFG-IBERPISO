<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function property()
    {
        return $this->belongsTo(Property::class, 'propiedad_id');
    }

    public function propiedad()
    {
        return $this->property();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function usuario()
    {
        return $this->user();
    }

    public function getSenderNameAttribute(): string
    {
        return $this->user ? $this->user->nombre : ($this->nombre_visitante ?? 'Desconocido');
    }

    public function getSenderEmailAttribute(): ?string
    {
        return $this->user ? $this->user->correo : $this->correo_visitante;
    }

    public function scopePending($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeUnread($query)
    {
        return $query->where('leida', \DB::raw('false'));
    }
}
