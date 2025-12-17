<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'recipient_name',
        'recipient_phone',
        'street_address',
        'street_number',
        'apartment',
        'neighborhood',
        'city',
        'state',
        'postal_code',
        'country',
        'additional_info',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // Scopes
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // Methods
    public function setAsDefault(): void
    {
        // Remove default from other addresses
        $this->user->addresses()->update(['is_default' => false]);

        // Set this as default
        $this->update(['is_default' => true]);
    }

    public function getFullAddressAttribute(): string
    {
        $parts = [
            $this->street_address . ' ' . $this->street_number,
            $this->apartment ? 'Piso/Depto: ' . $this->apartment : null,
            $this->neighborhood,
            $this->city,
            $this->state,
            'CP: ' . $this->postal_code,
            $this->country,
        ];

        return implode(', ', array_filter($parts));
    }
}