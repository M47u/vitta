<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        
        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(10),
            'long_description' => fake()->paragraph(5),
            'sku' => 'VP-' . strtoupper(fake()->unique()->bothify('???-###')),
            'base_price' => fake()->randomFloat(2, 5000, 50000),
            'discount_price' => null,
            'discount_percentage' => null,
            'category_id' => null, // Se debe crear la categoría en el test
            'brand' => fake()->randomElement(['Carolina Herrera', 'Dior', 'Chanel', 'Paco Rabanne', 'Hugo Boss', 'Dolce & Gabbana']),
            'fragrance_family' => fake()->randomElement(['floral', 'oriental', 'woody', 'fresh', 'citrus', 'spicy']),
            'gender' => fake()->randomElement(['unisex', 'masculine', 'feminine']),
            'is_featured' => fake()->boolean(30),
            'is_active' => true,
            'images' => [],
            'meta_tags' => [],
            'package_width' => 8.0,
            'package_height' => 12.0,
            'package_length' => 8.0,
            'package_weight' => 250,
        ];
    }

    /**
     * Indicate that the product is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the product has a discount.
     */
    public function onSale(): static
    {
        return $this->state(function (array $attributes) {
            $basePrice = $attributes['base_price'];
            $discountPercentage = fake()->numberBetween(10, 40);
            $discountPrice = $basePrice * (1 - $discountPercentage / 100);
            
            return [
                'discount_price' => round($discountPrice, 2),
                'discount_percentage' => $discountPercentage,
            ];
        });
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
