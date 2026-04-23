<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyInteraction extends Model
{
    protected $fillable = [
        'user_id',
        'property_id',
        'type',
    ];
}
