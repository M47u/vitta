<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cart::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'session_id' => fake()->uuid(),
            'subtotal' => 0,
            'discount' => 0,
            'tax' => 0,
            'total' => 0,
            'expires_at' => now()->addDays(30),
        ];
    }

    /**
     * Indicate that the cart has calculated totals.
     */
    public function withTotals(float $subtotal, float $discount = 0): static
    {
        return $this->state(function (array $attributes) use ($subtotal, $discount) {
            $totalConIVA = $subtotal;
            $subtotalSinIVA = $totalConIVA / 1.21;
            $tax = $totalConIVA - $subtotalSinIVA;
            
            return [
                'subtotal' => round($subtotalSinIVA, 2),
                'discount' => $discount,
                'tax' => round($tax, 2),
                'total' => round($totalConIVA - $discount, 2),
            ];
        });
    }
}
