<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyInteraction extends Model
{
    protected $table = 'interacciones_propiedades';

    protected $fillable = [
        'usuario_id',
        'propiedad_id',
        'tipo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'propiedad_id');
    }
}
