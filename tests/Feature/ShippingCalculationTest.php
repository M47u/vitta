<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Setting;
use App\Services\MercadoEnviosService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;

class ShippingCalculationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $mercadoEnvios;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mercadoEnvios = new MercadoEnviosService();
        
        // Configurar settings de prueba
        Setting::set('shipping_method', 'weight');
        Setting::set('shipping_base_cost', 1500);
        Setting::set('shipping_cost_per_kg', 800);
        Setting::set('shipping_cost', 2500);
        Setting::set('free_shipping_minimum', 50000);
    }

    /** @test */
    public function test_shipping_calculation_by_weight_below_base()
    {
        // Prueba: 400g debe costar $1,500 (costo base)
        $cost = $this->mercadoEnvios->calculateShippingByWeight(400);
        
        $this->assertEquals(1500, $cost, 
            "400g debe costar $1,500 (costo base)"
        );
    }

    /** @test */
    public function test_shipping_calculation_by_weight_exactly_base()
    {
        // Prueba: 500g debe costar $1,500 (costo base exacto)
        $cost = $this->mercadoEnvios->calculateShippingByWeight(500);
        
        $this->assertEquals(1500, $cost,
            "500g debe costar $1,500 (costo base exacto)"
        );
    }

    /** @test */
    public function test_shipping_calculation_by_weight_800g()
    {
        // Prueba: 800g = 500g base + 300g adicional
        // 300g = 0.3kg -> ceil(0.3) = 1kg adicional
        // Costo = $1,500 + (1 × $800) = $2,300
        $cost = $this->mercadoEnvios->calculateShippingByWeight(800);
        
        $this->assertEquals(2300, $cost,
            "800g debe costar $2,300 (base + 1kg adicional)"
        );
    }

    /** @test */
    public function test_shipping_calculation_by_weight_1200g()
    {
        // Prueba: 1200g = 500g base + 700g adicional
        // 700g = 0.7kg -> ceil(0.7) = 1kg adicional
        // Costo = $1,500 + (1 × $800) = $2,300
        $cost = $this->mercadoEnvios->calculateShippingByWeight(1200);
        
        $this->assertEquals(2300, $cost,
            "1200g debe costar $2,300 (base + 1kg adicional redondeado)"
        );
    }

    /** @test */
    public function test_shipping_calculation_by_weight_1800g()
    {
        // Prueba: 1800g = 500g base + 1300g adicional
        // 1300g = 1.3kg -> ceil(1.3) = 2kg adicionales
        // Costo = $1,500 + (2 × $800) = $3,100
        $cost = $this->mercadoEnvios->calculateShippingByWeight(1800);
        
        $this->assertEquals(3100, $cost,
            "1800g debe costar $3,100 (base + 2kg adicionales)"
        );
    }

    /** @test */
    public function test_shipping_calculation_by_weight_3000g()
    {
        // Prueba: 3000g = 500g base + 2500g adicional
        // 2500g = 2.5kg -> ceil(2.5) = 3kg adicionales
        // Costo = $1,500 + (3 × $800) = $3,900
        $cost = $this->mercadoEnvios->calculateShippingByWeight(3000);
        
        $this->assertEquals(3900, $cost,
            "3000g debe costar $3,900 (base + 3kg adicionales)"
        );
    }

    /** @test */
    public function test_package_dimensions_calculation_single_item()
    {
        // Crear categoría primero
        $category = \App\Models\Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Test',
            'is_active' => true,
        ]);
        
        // Crear un producto con dimensiones específicas
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'package_width' => 10,
            'package_height' => 15,
            'package_length' => 10,
            'package_weight' => 300,
            'base_price' => 10000,
        ]);

        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        
        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->base_price,
            'subtotal' => $product->base_price,
        ]);

        $dimensions = $this->mercadoEnvios->calculatePackageDimensions(collect([$cartItem]));

        $this->assertEquals(300, $dimensions['weight'], 'Peso debe ser 300g');
        $this->assertEquals(10, $dimensions['width'], 'Ancho debe ser 10cm');
        $this->assertEquals(15, $dimensions['height'], 'Alto debe ser 15cm');
        $this->assertEquals(10, $dimensions['length'], 'Largo debe ser 10cm');
    }

    /** @test */
    public function test_package_dimensions_calculation_multiple_items()
    {
        // Crear categoría primero
        $category = \App\Models\Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Test',
            'is_active' => true,
        ]);
        
        $product1 = Product::factory()->create([
            'category_id' => $category->id,
            'package_width' => 8,
            'package_height' => 12,
            'package_length' => 8,
            'package_weight' => 250,
        ]);

        $product2 = Product::factory()->create([
            'category_id' => $category->id,
            'package_width' => 10,
            'package_height' => 15,
            'package_length' => 10,
            'package_weight' => 300,
        ]);

        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        
        $cartItem1 = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product1->id,
            'quantity' => 2,
            'price' => 10000,
            'subtotal' => 20000,
        ]);

        $cartItem2 = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product2->id,
            'quantity' => 1,
            'price' => 12000,
            'subtotal' => 12000,
        ]);

        $dimensions = $this->mercadoEnvios->calculatePackageDimensions(collect([$cartItem1, $cartItem2]));

        // Peso total = (250g × 2) + (300g × 1) = 800g
        $this->assertEquals(800, $dimensions['weight'], 'Peso total debe ser 800g');
        
        // Ancho = max(8, 10) = 10cm
        $this->assertEquals(10, $dimensions['width'], 'Ancho debe ser el máximo: 10cm');
        
        // Alto = max(12, 15) = 15cm
        $this->assertEquals(15, $dimensions['height'], 'Alto debe ser el máximo: 15cm');
        
        // Largo = (8 × 2) + (10 × 1) = 26cm
        $this->assertEquals(26, $dimensions['length'], 'Largo debe sumar todos los items: 26cm');
    }

    /** @test */
    public function test_cart_totals_calculation_with_iva()
    {
        // Crear categoría primero
        $category = \App\Models\Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Test',
            'is_active' => true,
        ]);
        
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'base_price' => 12100, // Precio con IVA incluido
        ]);

        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => $product->base_price,
            'subtotal' => $product->base_price * 2, // 24200
        ]);

        $cart->calculateTotals();
        $cart->refresh();

        // Total con IVA = 24200
        // Subtotal sin IVA = 24200 / 1.21 = 20000
        // IVA = 24200 - 20000 = 4200
        // Total = 24200 - 0 (sin descuentos) = 24200

        $this->assertEquals(20000, round($cart->subtotal), 'Subtotal sin IVA debe ser 20000');
        $this->assertEquals(4200, round($cart->tax), 'IVA debe ser 4200');
        $this->assertEquals(24200, round($cart->total), 'Total debe ser 24200');
    }

    /** @test */
    public function test_free_shipping_threshold()
    {
        Setting::set('free_shipping_minimum', 50000);

        $dimensions = [
            'weight' => 500,
            'width' => 10,
            'height' => 15,
            'length' => 10,
            'item_price' => 55000, // Por encima del mínimo
        ];

        $result = $this->mercadoEnvios->calculateShippingCost('1636', '1425', $dimensions);

        $this->assertEquals(0, $result['cost'], 'Envío debe ser gratis por superar el mínimo');
        $this->assertEquals('free', $result['method'], 'Método debe ser "free"');
    }

    /** @test */
    public function test_fixed_shipping_method()
    {
        Setting::set('shipping_method', 'fixed');
        Setting::set('shipping_cost', 2500);

        $dimensions = [
            'weight' => 500,
            'width' => 10,
            'height' => 15,
            'length' => 10,
            'item_price' => 10000,
        ];

        $result = $this->mercadoEnvios->calculateShippingCost('1636', '1425', $dimensions);

        $this->assertEquals(2500, $result['cost'], 'Costo fijo debe ser 2500');
        $this->assertEquals('fixed', $result['method'], 'Método debe ser "fixed"');
    }

    /** @test */
    public function test_weight_shipping_method()
    {
        Setting::set('shipping_method', 'weight');

        $dimensions = [
            'weight' => 800, // Debe costar $2,300 según nuestras pruebas anteriores
            'width' => 10,
            'height' => 15,
            'length' => 10,
            'item_price' => 10000,
        ];

        $result = $this->mercadoEnvios->calculateShippingCost('1636', '1425', $dimensions);

        $this->assertEquals(2300, $result['cost'], 'Costo por peso de 800g debe ser 2300');
        $this->assertEquals('weight', $result['method'], 'Método debe ser "weight"');
    }
}
