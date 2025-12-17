<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'long_description',
        'sku',
        'base_price',
        'discount_price',
        'discount_percentage',
        'category_id',
        'brand',
        'fragrance_family',
        'gender',
        'is_featured',
        'is_active',
        'images',
        'meta_tags',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'images' => 'array',
        'meta_tags' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function fragranceNotes(): BelongsToMany
    {
        return $this->belongsToMany(FragranceNote::class, 'product_fragrance_note');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wishlists(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }

    // Accessors
    protected function currentPrice(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->discount_price ?? $this->base_price,
        );
    }

    protected function mainImage(): Attribute
    {
        return Attribute::make(
            get: fn() => !empty($this->images) && is_array($this->images) ? $this->images[0] : null,
        );
    }

    protected function isOnSale(): Attribute
    {
        return Attribute::make(
            get: fn() => !is_null($this->discount_price) && $this->discount_price < $this->base_price,
        );
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('is_active', true);
    }

    public function scopeByFragranceFamily($query, string $family)
    {
        return $query->where('fragrance_family', $family);
    }

    public function scopePriceRange($query, float $min, float $max)
    {
        return $query->whereBetween('base_price', [$min, $max]);
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}