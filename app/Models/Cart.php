<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'subtotal',
        'discount',
        'tax',
        'total',
        'expires_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function calculateTotals(): void
    {
        $this->subtotal = $this->items->sum('subtotal');
        $this->tax = $this->subtotal * 0.21; // IVA Argentina 21%
        $this->total = $this->subtotal + $this->tax - $this->discount;
        $this->save();
    }

    public function isEmpty(): bool
    {
        return $this->items()->count() === 0;
    }

    public function getItemsCount(): int
    {
        return $this->items()->sum('quantity');
    }
}