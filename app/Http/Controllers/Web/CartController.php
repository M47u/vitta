<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    protected function getOrCreateCart()
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(
                ['user_id' => Auth::id()],
                ['expires_at' => now()->addDays(30)]
            );
        } else {
            $sessionId = Session::getId();
            $cart = Cart::firstOrCreate(
                ['session_id' => $sessionId],
                ['expires_at' => now()->addDays(7)]
            );
        }

        return $cart;
    }

    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load(['items.product', 'items.productVariant']);

        return view('cart.index', compact('cart'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = $request->variant_id ? ProductVariant::findOrFail($request->variant_id) : null;

        // Validar stock
        $availableStock = $variant ? $variant->stock : 100; // Si no hay variante, asumimos stock
        if ($request->quantity > $availableStock) {
            return response()->json([
                'success' => false,
                'message' => "Stock insuficiente. Disponible: {$availableStock}"
            ], 400);
        }

        $cart = $this->getOrCreateCart();
        $price = $variant ? $variant->price : $product->current_price;

        // Verificar si ya existe el item
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->where('product_variant_id', $variant?->id)
            ->first();

        if ($cartItem) {
            // Actualizar cantidad
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($newQuantity > $availableStock) {
                return response()->json([
                    'success' => false,
                    'message' => "No puedes agregar más. Stock disponible: {$availableStock}"
                ], 400);
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->subtotal = $newQuantity * $price;
            $cartItem->save();
        } else {
            // Crear nuevo item
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'product_variant_id' => $variant?->id,
                'quantity' => $request->quantity,
                'price' => $price,
                'subtotal' => $request->quantity * $price,
            ]);
        }

        $cart->calculateTotals();

        return response()->json([
            'success' => true,
            'message' => '¡Producto agregado al carrito!',
            'cart_count' => $cart->getItemsCount(),
            'cart_total' => number_format($cart->total, 0, ',', '.')
        ]);
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $cartItem = CartItem::findOrFail($itemId);
        $cart = $cartItem->cart;

        // Verificar propiedad del carrito
        if (Auth::check() && $cart->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }
        if (!Auth::check() && $cart->session_id !== Session::getId()) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        if ($request->quantity == 0) {
            $cartItem->delete();
        } else {
            // Validar stock
            $variant = $cartItem->productVariant;
            $availableStock = $variant ? $variant->stock : 100;

            if ($request->quantity > $availableStock) {
                return response()->json([
                    'success' => false,
                    'message' => "Stock insuficiente. Disponible: {$availableStock}"
                ], 400);
            }

            $cartItem->quantity = $request->quantity;
            $cartItem->subtotal = $request->quantity * $cartItem->price;
            $cartItem->save();
        }

        $cart->calculateTotals();

        $freeShippingMinimum = Setting::get('free_shipping_minimum', 50000);

        return response()->json([
            'success' => true,
            'message' => 'Carrito actualizado',
            'cart_count' => $cart->getItemsCount(),
            'cart_subtotal' => number_format($cart->subtotal, 0, ',', '.'),
            'cart_tax' => number_format($cart->tax, 0, ',', '.'),
            'cart_total' => number_format($cart->total, 0, ',', '.'),
            'item_subtotal' => $cartItem->exists ? number_format($cartItem->subtotal, 0, ',', '.') : 0,
            'free_shipping_remaining' => max(0, $freeShippingMinimum - $cart->subtotal),
            'has_free_shipping' => $cart->subtotal >= $freeShippingMinimum
        ]);
    }

    public function destroy($itemId)
    {
        $cartItem = CartItem::findOrFail($itemId);
        $cart = $cartItem->cart;

        // Verificar propiedad
        if (Auth::check() && $cart->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'No autorizado');
        }
        if (!Auth::check() && $cart->session_id !== Session::getId()) {
            return redirect()->back()->with('error', 'No autorizado');
        }

        $cartItem->delete();
        $cart->calculateTotals();

        return redirect()->back()->with('success', 'Producto eliminado del carrito');
    }
}