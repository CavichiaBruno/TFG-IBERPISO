<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articulos';

    protected $fillable = [
        'titulo', 'slug', 'contenido', 'autor', 'imagen_url', 
        'categoria', 'publicado', 'fecha_publicacion'
    ];

    protected $casts = [
        'publicado' => 'boolean',
        'fecha_publicacion' => 'datetime',
    ];
}
