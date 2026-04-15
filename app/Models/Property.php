<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Property extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'description', 'price', 'surface_m2',
        'rooms', 'bathrooms', 'floor', 'property_type', 'operation_type',
        'address', 'city', 'province', 'postal_code', 'latitude', 'longitude',
        'has_elevator', 'has_parking', 'has_terrace', 'has_garden', 'has_pool',
        'air_conditioning', 'is_featured', 'is_active', 'energy_certificate',
        'virtual_tour_url',
    ];

    protected $casts = [
        'price'           => 'decimal:2',
        'surface_m2'      => 'decimal:2',
        'latitude'        => 'decimal:7',
        'longitude'       => 'decimal:7',
        'has_elevator'    => 'boolean',
        'has_parking'     => 'boolean',
        'has_terrace'     => 'boolean',
        'has_garden'      => 'boolean',
        'has_pool'        => 'boolean',
        'air_conditioning'=> 'boolean',
        'is_featured'     => 'boolean',
        'is_active'       => 'boolean',
    ];

    // --- Relationships ---

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->hasMany(PropertyMedia::class)->orderBy('sort_order');
    }

    public function images()
    {
        return $this->hasMany(PropertyMedia::class)->where('file_type', 'image')->orderBy('sort_order');
    }

    public function documents()
    {
        return $this->hasMany(PropertyMedia::class)->where('file_type', 'pdf')->orderBy('sort_order');
    }

    public function coverImage()
    {
        return $this->hasOne(PropertyMedia::class)->where('file_type', 'image')->where('is_cover', true);
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }

    // --- Scopes ---

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeFilterByType($query, string $type)
    {
        return $query->where('property_type', $type);
    }

    public function scopeFilterByOperation($query, string $operation)
    {
        return $query->where('operation_type', $operation);
    }

    // --- Accessors ---

    public function getSlugAttribute(): string
    {
        return Str::slug($this->title);
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', '.');
    }

    public function getCoverUrlAttribute(): ?string
    {
        $cover = $this->media->where('file_type', 'image')->where('is_cover', true)->first()
            ?? $this->media->where('file_type', 'image')->first();

        return $cover ? asset('storage/' . $cover->file_path) : asset('images/placeholder.jpg');
    }

    public static function getPropertyTypes(): array
    {
        return ['piso', 'casa', 'chalet', 'local', 'garaje', 'oficina'];
    }

    public static function getOperationTypes(): array
    {
        return ['venta', 'alquiler'];
    }

    public static function getEnergyCertificates(): array
    {
        return ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
    }
}
