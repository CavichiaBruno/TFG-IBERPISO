<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAgent(): bool
    {
        return $this->role === 'agent';
    }

    public function hasAdminAccess(): bool
    {
        return in_array($this->role, ['admin', 'agent']);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }

    public function interactions()
    {
        return $this->hasMany(PropertyInteraction::class);
    }

    public function favoriteProperties()
    {
        return $this->belongsToMany(Property::class, 'property_interactions')
            ->wherePivot('type', '=', 'like')
            ->withTimestamps();
    }
}
