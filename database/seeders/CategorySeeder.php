<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Oud Premium',
                'slug' => 'oud-premium',
                'description' => 'Fragancias con oud auténtico de la más alta calidad',
                'image' => '/images/categories/oud.jpg',
            ],
            [
                'name' => 'Musk Oriental',
                'slug' => 'musk-oriental',
                'description' => 'Almizcles suaves y envolventes',
                'image' => '/images/categories/musk.jpg',
            ],
            [
                'name' => 'Bakhoor',
                'slug' => 'bakhoor',
                'description' => 'Inciensos y maderas aromáticas tradicionales',
                'image' => '/images/categories/bakhoor.jpg',
            ],
            [
                'name' => 'Aceites Concentrados',
                'slug' => 'aceites-concentrados',
                'description' => 'Aceites puros de larga duración',
                'image' => '/images/categories/oils.jpg',
            ],
            [
                'name' => 'Inspiraciones',
                'slug' => 'inspiraciones',
                'description' => 'Fragancias inspiradas en las mejores casas de lujo',
                'image' => '/images/categories/inspired.jpg',
            ],
            [
                'name' => 'Unisex',
                'slug' => 'unisex',
                'description' => 'Para todos los gustos y personalidades',
                'image' => '/images/categories/unisex.jpg',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}