<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = [
        'property_id', 'user_id', 'guest_name', 'guest_email',
        'guest_phone', 'message', 'status', 'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSenderNameAttribute(): string
    {
        return $this->user ? $this->user->name : ($this->guest_name ?? 'Desconocido');
    }

    public function getSenderEmailAttribute(): ?string
    {
        return $this->user ? $this->user->email : $this->guest_email;
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
