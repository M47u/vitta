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
            // Endpoint de cotización de MercadoEnvíos
            $url = "{$this->baseUrl}/shipments/options";

            $payload = [
                'zip_code_from' => $zipCodeFrom,
                'zip_code_to' => $zipCodeTo,
                'dimensions' => implode('x', [
                    $dimensions['width'] ?? 15,
                    $dimensions['height'] ?? 10,
                    $dimensions['length'] ?? 10
                ]),
                'weight' => $dimensions['weight'] ?? 300, // gramos
                'item_price' => $dimensions['item_price'] ?? 1000, // precio del producto
                'free_shipping' => false
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->get($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                
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
                'response' => $response->json()
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('MercadoEnvíos Exception: ' . $e->getMessage());
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
                    $dimensions['width'] ?? 15,
                    $dimensions['height'] ?? 10,
                    $dimensions['length'] ?? 10
                ]),
                'weight' => $dimensions['weight'] ?? 300,
                'item_price' => $dimensions['item_price'] ?? 1000,
                'free_shipping' => false
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])->get($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                return $data['options'] ?? [];
            }

            return [];

        } catch (\Exception $e) {
            Log::error('Error getting shipping options: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Calcula dimensiones del paquete según items del carrito
     */
    public function calculatePackageDimensions($cartItems): array
    {
        // Para perfumes, asumimos dimensiones estándar por producto
        // Esto puede personalizarse según el tipo de producto
        
        $totalWeight = 0;
        $totalVolume = 0;

        foreach ($cartItems as $item) {
            // Peso estimado por perfume: 300g (incluye packaging)
            $weightPerItem = 300;
            $totalWeight += $weightPerItem * $item->quantity;
            
            // Volumen por perfume: 15x10x10 cm = 1500 cm³
            $volumePerItem = 1500;
            $totalVolume += $volumePerItem * $item->quantity;
        }

        // Calculamos dimensiones del paquete
        // Para simplificar, usamos una caja estándar que crece con la cantidad
        $itemCount = $cartItems->sum('quantity');
        
        if ($itemCount <= 2) {
            $dimensions = ['width' => 15, 'height' => 10, 'length' => 15];
        } elseif ($itemCount <= 4) {
            $dimensions = ['width' => 20, 'height' => 15, 'length' => 15];
        } else {
            $dimensions = ['width' => 25, 'height' => 20, 'length' => 20];
        }

        return [
            'weight' => $totalWeight,
            'width' => $dimensions['width'],
            'height' => $dimensions['height'],
            'length' => $dimensions['length']
        ];
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
