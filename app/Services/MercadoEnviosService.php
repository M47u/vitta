<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MercadoEnviosService
{
    private string $accessToken;
    private string $baseUrl;

    public function __construct()
    {
        $this->accessToken = config('services.mercadopago.access_token');
        $this->baseUrl = 'https://api.mercadolibre.com';
    }

    /**
     * Calcula el costo de envío desde origen a destino
     *
     * @param string $zipCodeFrom Código postal origen
     * @param string $zipCodeTo Código postal destino
     * @param array $dimensions ['weight' => gramos, 'height' => cm, 'width' => cm, 'length' => cm]
     * @return array|null ['cost' => monto, 'delivery_time' => días, 'options' => []]
     */
    public function calculateShipping(string $zipCodeFrom, string $zipCodeTo, array $dimensions): ?array
    {
        try {
            // Validar que tenemos access token
            if (empty($this->accessToken)) {
                Log::error('MercadoEnvíos: Access token no configurado');
                return null;
            }

            // Endpoint de cotización de MercadoEnvíos
            $url = "{$this->baseUrl}/shipments/options";

            // Usar las dimensiones calculadas (sin sobrescribir con defaults)
            $payload = [
                'zip_code_from' => $zipCodeFrom,
                'zip_code_to' => $zipCodeTo,
                'dimensions' => implode('x', [
                    $dimensions['width'],
                    $dimensions['height'],
                    $dimensions['length']
                ]),
                'weight' => $dimensions['weight'], // gramos
                'item_price' => $dimensions['item_price'], // precio del producto
                'free_shipping' => false
            ];

            Log::info('MercadoEnvíos Request', [
                'url' => $url,
                'payload' => $payload
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->get($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('MercadoEnvíos Response', ['data' => $data]);
                
                // MercadoEnvíos devuelve múltiples opciones (domicilio, sucursal, etc)
                if (!empty($data['options'])) {
                    $cheapestOption = collect($data['options'])->sortBy('cost')->first();
                    
                    return [
                        'cost' => $cheapestOption['cost'] ?? 0,
                        'delivery_time' => $cheapestOption['estimated_delivery_time']['date'] ?? null,
                        'delivery_days' => $cheapestOption['estimated_delivery_time']['shipping'] ?? null,
                        'options' => $data['options'], // Todas las opciones disponibles
                        'currency_id' => $cheapestOption['currency_id'] ?? 'ARS'
                    ];
                }
            }

            Log::error('MercadoEnvíos API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'payload_sent' => $payload
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('MercadoEnvíos Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Obtiene todas las opciones de envío disponibles
     */
    public function getShippingOptions(string $zipCodeFrom, string $zipCodeTo, array $dimensions): array
    {
        try {
            $url = "{$this->baseUrl}/shipments/options";

            $payload = [
                'zip_code_from' => $zipCodeFrom,
                'zip_code_to' => $zipCodeTo,
                'dimensions' => implode('x', [
                    $dimensions['width'],
                    $dimensions['height'],
                    $dimensions['length']
                ]),
                'weight' => $dimensions['weight'],
                'item_price' => $dimensions['item_price'],
                'free_shipping' => false
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])->get($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                return $data['options'] ?? [];
            }

            Log::error('Error getting shipping options', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [];

        } catch (\Exception $e) {
            Log::error('Error getting shipping options', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [];
        }
    }

    /**
     * Calcula dimensiones del paquete según items del carrito
     * Usa las dimensiones reales del producto si están disponibles
     */
    public function calculatePackageDimensions($cartItems): array
    {
        $totalWeight = 0;
        $maxWidth = 0;
        $maxHeight = 0;
        $totalLength = 0;

        foreach ($cartItems as $item) {
            $product = $item->product;
            
            // Usar dimensiones del producto o valores por defecto
            $width = $product->package_width ?? 8;
            $height = $product->package_height ?? 12;
            $length = $product->package_length ?? 8;
            $weight = $product->package_weight ?? 250;
            
            // Acumular peso total
            $totalWeight += $weight * $item->quantity;
            
            // Calcular dimensiones del paquete
            // El ancho y alto toman el máximo de todos los productos
            $maxWidth = max($maxWidth, $width);
            $maxHeight = max($maxHeight, $height);
            
            // El largo se suma si hay múltiples items
            $totalLength += $length * $item->quantity;
        }

        // Ajustar dimensiones según cantidad de items
        $itemCount = $cartItems->sum('quantity');
        
        // Si hay muchos items, ajustamos las dimensiones de la caja
        if ($itemCount > 3) {
            $maxWidth = min($maxWidth * 1.5, 30); // Máximo 30cm
            $maxHeight = min($maxHeight * 1.2, 25); // Máximo 25cm
        }
        
        // El largo no debe exceder límites de envío (100cm típicamente)
        $totalLength = min($totalLength, 100);
        
        // Redondear a enteros para MercadoEnvíos
        $dimensions = [
            'weight' => (int) ceil($totalWeight),
            'width' => (int) ceil($maxWidth),
            'height' => (int) ceil($maxHeight),
            'length' => (int) ceil($totalLength)
        ];
        
        Log::info('Dimensiones calculadas del paquete', [
            'items_count' => $itemCount,
            'dimensions' => $dimensions
        ]);

        return $dimensions;
    }

    /**
     * Calcula costo de envío basado en peso del paquete
     * Método alternativo cuando MercadoEnvíos no está disponible
     * 
     * @param int $weightInGrams Peso total en gramos
     * @return float Costo de envío en ARS
     */
    public function calculateShippingByWeight(int $weightInGrams): float
    {
        // Configuración desde settings o valores por defecto
        $baseCost = (float) \App\Models\Setting::get('shipping_base_cost', 1500); // Hasta 500g
        $costPerKg = (float) \App\Models\Setting::get('shipping_cost_per_kg', 800);
        $baseWeight = 500; // gramos incluidos en el costo base

        Log::info('Calculando envío por peso', [
            'weight_grams' => $weightInGrams,
            'base_cost' => $baseCost,
            'cost_per_kg' => $costPerKg
        ]);

        // Si el peso está dentro del rango base
        if ($weightInGrams <= $baseWeight) {
            return $baseCost;
        }

        // Calcular peso adicional en kg
        $additionalWeight = ($weightInGrams - $baseWeight) / 1000;
        
        // Redondear hacia arriba (se cobra por kg completo)
        $additionalKg = ceil($additionalWeight);
        
        // Costo total = base + (kg adicionales × costo por kg)
        $totalCost = $baseCost + ($additionalKg * $costPerKg);

        Log::info('Costo de envío calculado por peso', [
            'base_cost' => $baseCost,
            'additional_kg' => $additionalKg,
            'additional_cost' => $additionalKg * $costPerKg,
            'total_cost' => $totalCost
        ]);

        return $totalCost;
    }

    /**
     * Calcula envío usando el método configurado en settings
     * 
     * @param string $zipCodeFrom Código postal origen
     * @param string $zipCodeTo Código postal destino  
     * @param array $dimensions Array con weight, width, height, length, item_price
     * @return array ['cost' => float, 'method' => string, 'details' => array]
     */
    public function calculateShippingCost(string $zipCodeFrom, string $zipCodeTo, array $dimensions): array
    {
        $method = \App\Models\Setting::get('shipping_method', 'mercadoenvios');
        $freeShippingMin = (float) \App\Models\Setting::get('free_shipping_minimum', 50000);
        $itemPrice = $dimensions['item_price'] ?? 0;

        // Verificar envío gratis
        if ($itemPrice >= $freeShippingMin) {
            return [
                'cost' => 0,
                'method' => 'free',
                'details' => [
                    'message' => '¡Envío gratis por compra mayor a $' . number_format($freeShippingMin, 0, ',', '.')
                ]
            ];
        }

        switch ($method) {
            case 'mercadoenvios':
                // Intentar con API de MercadoEnvíos
                $result = $this->calculateShipping($zipCodeFrom, $zipCodeTo, $dimensions);
                
                if ($result && isset($result['cost'])) {
                    return [
                        'cost' => $result['cost'],
                        'method' => 'mercadoenvios',
                        'details' => $result
                    ];
                }
                
                // Fallback a cálculo por peso si MercadoEnvíos falla
                Log::warning('MercadoEnvíos falló, usando cálculo por peso como fallback');
                $cost = $this->calculateShippingByWeight($dimensions['weight']);
                return [
                    'cost' => $cost,
                    'method' => 'weight_fallback',
                    'details' => ['weight' => $dimensions['weight']]
                ];

            case 'weight':
                // Cálculo por peso
                $cost = $this->calculateShippingByWeight($dimensions['weight']);
                return [
                    'cost' => $cost,
                    'method' => 'weight',
                    'details' => ['weight' => $dimensions['weight']]
                ];

            case 'fixed':
            default:
                // Costo fijo
                $fixedCost = (float) \App\Models\Setting::get('shipping_cost', 2500);
                return [
                    'cost' => $fixedCost,
                    'method' => 'fixed',
                    'details' => ['fixed_rate' => $fixedCost]
                ];
        }
    }

    /**
     * Crea un envío en MercadoEnvíos
     * Se ejecuta después de que el pago fue confirmado
     */
    public function createShipment(array $shipmentData): ?array
    {
        try {
            $url = "{$this->baseUrl}/shipments";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post($url, $shipmentData);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Error creating shipment', [
                'status' => $response->status(),
                'response' => $response->json()
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('Shipment creation exception: ' . $e->getMessage());
            return null;
        }
    }
}
