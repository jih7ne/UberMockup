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
        'user_type',
        'phone',
        'address',
        'city',
        'country',
        'latitude',
        'longitude',
        'vehicle_type',
        'vehicle_model',
        'vehicle_plate',
        'license_number',
        'is_available',
        'rating',
        'total_deliveries',
        'bio',
        'profile_photo',
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
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'is_available' => 'boolean',
            'rating' => 'decimal:2',
        ];
    }
    
    public function isDriver(): bool
    {
        return $this->user_type === 'driver';
    }
    
    public function isClient(): bool
    {
        return $this->user_type === 'client';
    }

    // Scope pour récupérer uniquement les drivers disponibles
    public function scopeAvailableDrivers($query)
    {
        return $query->where('user_type', 'driver')
                    ->where('is_available', true)
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude');
    }
}