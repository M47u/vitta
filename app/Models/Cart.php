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
        // Los precios en el catálogo ya incluyen IVA
        $totalConIVA = $this->items->sum('subtotal');
        
        // Discriminar el IVA (precio con IVA / 1.21 = precio sin IVA)
        $this->subtotal = $totalConIVA / 1.21; // Subtotal sin IVA
        $this->tax = $totalConIVA - $this->subtotal; // IVA discriminado
        $this->total = $totalConIVA - $this->discount; // Total = precio con IVA - descuentos
        
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