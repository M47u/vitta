<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'ml_size',
        'price',
        'stock',
        'min_stock',
        'is_active',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}