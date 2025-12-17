<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FragranceNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'icon',
        'description',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_fragrance_note');
    }
}