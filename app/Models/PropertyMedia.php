<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyMedia extends Model
{
    protected $fillable = [
        'property_id', 'file_path', 'file_type', 'mime_type',
        'file_size_kb', 'original_name', 'is_cover', 'sort_order',
    ];

    protected $casts = [
        'is_cover'  => 'boolean',
        'sort_order'=> 'integer',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function getUrlAttribute(): string
    {
        if (str_starts_with($this->file_path, 'data:')) {
            return $this->file_path;
        }
        return asset('storage/' . $this->file_path);
    }

    public function scopeImages($query)
    {
        return $query->where('file_type', 'image');
    }

    public function scopeDocuments($query)
    {
        return $query->where('file_type', 'pdf');
    }

    public function scopeVideos($query)
    {
        return $query->where('file_type', 'video');
    }
}
