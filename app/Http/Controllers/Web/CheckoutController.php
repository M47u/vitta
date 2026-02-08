<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Transaction;
use App\Mail\OrderConfirmation;
use App\Services\MercadoEnviosService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

class CheckoutController extends Controller
{
    /**
     * Show checkout page with shipping info
     */
    public function index()
    {
        $cart = Cart::with(['items.product', 'items.productVariant'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Tu carrito está vacío');
        }

        $addresses = Address::where('user_id', auth()->id())->get();
        $defaultAddress = $addresses->where('is_default', true)->first();

        return view('checkout.index', compact('cart', 'addresses', 'defaultAddress'));
    }

    /**
     * Store or update shipping address
     */
    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'street_address' => 'required|string|max:255',
            'street_number' => 'required|string|max:10',
            'apartment' => 'nullable|string|max:50',
            'neighborhood' => 'nullable|string|max:100',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|max:100',
            'additional_info' => 'nullable|string',
            'is_default' => 'boolean',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['label'] = $validated['label'] ?? 'Principal';

        $address = Address::create($validated);

        if ($request->is_default) {
            $address->setAsDefault();
        }

        return redirect()->route('checkout.payment', ['address' => $address->id])
            ->with('success', 'Dirección guardada correctamente');
    }

    /**
     * Calculate shipping cost with MercadoEnvíos (AJAX endpoint)
     */
    public function calculateShipping(Request $request, $addressId)
    {
        try {
            $cart = Cart::with(['items.product', 'items.productVariant'])
                ->where('user_id', auth()->id())
                ->first();

            if (!$cart || $cart->items->isEmpty()) {
                return response()->json(['error' => 'Carrito vacío'], 400);
            }

            $address = Address::where('user_id', auth()->id())
                ->findOrFail($addressId);

            $mercadoEnvios = new MercadoEnviosService();
            
            // Calcular dimensiones del paquete
            $dimensions = $mercadoEnvios->calculatePackageDimensions($cart->items);
            $dimensions['item_price'] = (int)$cart->total; // Precio para calcular seguro

            // Obtener CP origen desde config
            $zipCodeFrom = config('services.mercadoenvios.zip_code_from');
            
            // Calcular costo de envío usando el método configurado
            $shippingResult = $mercadoEnvios->calculateShippingCost(
                $zipCodeFrom,
                $address->postal_code,
                $dimensions
            );

            return response()->json([
                'success' => true,
                'cost' => $shippingResult['cost'],
                'method' => $shippingResult['method'],
                'delivery_time' => $shippingResult['details']['delivery_time'] ?? null,
                'delivery_days' => $shippingResult['details']['delivery_days'] ?? null,
                'options' => $shippingResult['details']['options'] ?? [],
                'message' => $shippingResult['details']['message'] ?? null
            ]);

        } catch (\Exception $e) {
            Log::error('Error calculating shipping: ' . $e->getMessage());
            
            return response()->json([
                'success' => true,
                'cost' => 2500,
                'method' => 'fallback',
                'message' => 'Costo de envío estimado'
            ]);
        }
    }

    /**
     * Show payment method selection
     */
    public function payment($addressId)
    {
        $cart = Cart::with(['items.product', 'items.productVariant'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Tu carrito está vacío');
        }

        $address = Address::where('user_id', auth()->id())
            ->findOrFail($addressId);

        // Use cart's calculated totals (includes IVA)
        $cart->calculateTotals();
        $subtotal = $cart->subtotal;
        $tax = $cart->tax;
        
        // Calcular envío usando el método configurado
        $mercadoEnvios = new MercadoEnviosService();
        $dimensions = $mercadoEnvios->calculatePackageDimensions($cart->items);
        $dimensions['item_price'] = (int)$cart->total;
        
        $zipCodeFrom = config('services.mercadoenvios.zip_code_from');
        $shippingResult = $mercadoEnvios->calculateShippingCost(
            $zipCodeFrom,
            $address->postal_code,
            $dimensions
        );
        
        $shipping = $shippingResult['cost'];
        $shippingMethod = $shippingResult['method'];
        $shippingOptions = $shippingResult['details']['options'] ?? [];
        
        $total = $cart->total + $shipping;

        return view('checkout.payment', compact(
            'cart', 
            'address', 
            'subtotal', 
            'tax', 
            'shipping', 
            'total',
            'shippingOptions'
        ));
    }

    /**
     * Process order and create MercadoPago preference
     */
    public function processOrder(Request $request, $addressId)
    {
        try {
            DB::beginTransaction();

            $cart = Cart::with(['items.product', 'items.productVariant'])
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $address = Address::where('user_id', auth()->id())
                ->findOrFail($addressId);

            // Use cart's calculated totals (includes IVA)
            $cart->calculateTotals();
            $subtotal = $cart->subtotal;
            $tax = $cart->tax;
            
            // Calcular envío usando el método configurado
            $mercadoEnvios = new MercadoEnviosService();
            $dimensions = $mercadoEnvios->calculatePackageDimensions($cart->items);
            $dimensions['item_price'] = (int)$cart->total;
            
            $zipCodeFrom = config('services.mercadoenvios.zip_code_from');
            $shippingResult = $mercadoEnvios->calculateShippingCost(
                $zipCodeFrom,
                $address->postal_code,
                $dimensions
            );
            
            $shipping = $shippingResult['cost'];
            $total = $cart->total + $shipping;

            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
                'address_id' => $address->id,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => 'mercadopago',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'shipping_address' => [
                    'recipient_name' => $address->recipient_name,
                    'recipient_phone' => $address->recipient_phone,
                    'full_address' => $address->full_address,
                ],
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($cart->items as $cartItem) {
                $price = $cartItem->product->discount_price ?? $cartItem->product->base_price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_variant_id' => $cartItem->product_variant_id,
                    'product_name' => $cartItem->product->name,
                    'sku' => $cartItem->product->sku,
                    'variant_name' => $cartItem->productVariant ? $cartItem->productVariant->name : null,
                    'quantity' => $cartItem->quantity,
                    'price' => $price,
                    'subtotal' => $price * $cartItem->quantity,
                ]);
            }

            // Create MercadoPago preference
            $preference = $this->createMercadoPagoPreference($order);

            if (!$preference) {
                throw new \Exception('Error al crear preferencia de MercadoPago');
            }

            // Create pending transaction
            Transaction::create([
                'order_id' => $order->id,
                'payment_platform' => 'mercadopago',
                'status' => 'pending',
                'amount' => $total,
                'currency' => 'ARS',
                'metadata' => [
                    'preference_id' => $preference->id,
                ],
            ]);

            DB::commit();

            // Clear cart after successful order
            $cart->items()->delete();
            $cart->delete();

            return response()->json([
                'success' => true,
                'preference_id' => $preference->id,
                'init_point' => $preference->init_point,
                'order_number' => $order->order_number,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing order: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la orden. Por favor, inténtalo de nuevo.',
            ], 500);
        }
    }

    /**
     * Create MercadoPago preference
     */
    private function createMercadoPagoPreference(Order $order)
    {
        try {
            $accessToken = config('services.mercadopago.access_token');
            
            if (empty($accessToken)) {
                Log::error('MercadoPago access token is not configured');
                return null;
            }

            // Initialize SDK
            MercadoPagoConfig::setAccessToken($accessToken);

            $client = new PreferenceClient();

            // Calcular el total a cobrar (subtotal + IVA + envío)
            $totalAmount = $order->total;

            // Crear un solo item con el total de la orden
            $items = [
                [
                    'title' => 'Pedido #' . $order->order_number . ' - Vitta Perfumes',
                    'description' => $order->items->count() . ' producto(s)',
                    'quantity' => 1,
                    'unit_price' => (float) $totalAmount,
                    'currency_id' => 'ARS',
                ]
            ];

            $preferenceData = [
                'items' => $items,
                'payer' => [
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                ],
                'back_urls' => [
                    'success' => route('checkout.success', ['order' => $order->id]),
                    'failure' => route('checkout.failure', ['order' => $order->id]),
                    'pending' => route('checkout.pending', ['order' => $order->id]),
                ],
                'external_reference' => $order->order_number,
                'statement_descriptor' => 'VITTA PERFUMES',
            ];

            Log::info('Creating MercadoPago preference', ['data' => $preferenceData]);

            // Create preference
            $preference = $client->create($preferenceData);

            Log::info('MercadoPago preference created', ['preference_id' => $preference->id]);

            return $preference;

        } catch (MPApiException $e) {
            Log::error('MercadoPago API Error: ' . $e->getMessage());
            Log::error('Status Code: ' . $e->getStatusCode());
            $response = $e->getApiResponse();
            if ($response) {
                Log::error('API Response: ' . json_encode($response->getContent()));
            }
            return null;
        } catch (\Exception $e) {
            Log::error('Error creating MercadoPago preference: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return null;
        }
    }

    /**
     * Handle success callback from MercadoPago
     */
    public function success(Request $request, Order $order)
    {
        // Update order status based on payment status
        if ($request->has('payment_id')) {
            $order->update([
                'payment_status' => 'processing',
                'status' => 'processing',
            ]);
        }

        return view('checkout.success', compact('order'));
    }

    /**
     * Handle failure callback from MercadoPago
     */
    public function failure(Request $request, Order $order)
    {
        $order->update([
            'payment_status' => 'rejected',
        ]);

        return view('checkout.failure', compact('order'));
    }

    /**
     * Handle pending callback from MercadoPago
     */
    public function pending(Request $request, Order $order)
    {
        $order->update([
            'payment_status' => 'pending',
        ]);

        return view('checkout.pending', compact('order'));
    }

    /**
     * Handle MercadoPago webhooks
     */
    public function webhook(Request $request)
    {
        try {
            $type = $request->input('type');

            if ($type === 'payment') {
                $paymentId = $request->input('data.id');

                // Initialize SDK
                SDK::setAccessToken(config('services.mercadopago.access_token'));

                // Get payment info
                $payment = \MercadoPago\Payment::find_by_id($paymentId);

                // Find order by external reference
                $order = Order::where('order_number', $payment->external_reference)->first();

                if ($order) {
                    // Update transaction
                    $transaction = Transaction::where('order_id', $order->id)->first();

                    if ($transaction) {
                        $transaction->update([
                            'transaction_id' => $payment->id,
                            'status' => $payment->status,
                            'payment_method' => $payment->payment_method_id,
                            'gateway_response' => json_encode($payment),
                            'processed_at' => now(),
                        ]);
                    }

                    // Update order based on payment status
                    if ($payment->status === 'approved') {
                        $order->markAsPaid();
                        
                        // Enviar email de confirmación de pedido
                        try {
                            Mail::to($order->user->email)->send(new OrderConfirmation($order));
                        } catch (\Exception $e) {
                            Log::error('Error sending order confirmation email: ' . $e->getMessage());
                        }
                    } elseif ($payment->status === 'rejected' || $payment->status === 'cancelled') {
                        $order->update([
                            'payment_status' => $payment->status,
                        ]);
                    }
                }
            }

            return response()->json(['success' => true], 200);

        } catch (\Exception $e) {
            Log::error('Error processing webhook: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}