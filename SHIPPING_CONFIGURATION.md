# Configuración de Envíos - Vitta Perfumes

## MercadoEnvíos (Implementado Actualmente)

### Descripción
Integración con MercadoEnvíos para cálculo automático de costos de envío basado en:
- Código postal de origen y destino
- Peso y dimensiones del paquete
- Precio del producto (para seguro)

### Configuración

#### 1. Variables de Entorno (.env)
```env
MERCADOPAGO_ACCESS_TOKEN=tu_access_token_aqui
MERCADOPAGO_PUBLIC_KEY=tu_public_key_aqui
MERCADOPAGO_SANDBOX=true  # false para producción

MERCADOENVIOS_ZIP_CODE_FROM=1636  # CP desde donde envías
```

#### 2. Flujo de Funcionamiento

1. **Al seleccionar dirección de envío:**
   - Se toma el CP de destino del Address del usuario
   - Se calculan dimensiones del paquete según items del carrito
   - Se consulta la API de MercadoEnvíos

2. **Cálculo de Dimensiones (por defecto):**
   ```
   1-2 productos:  15x10x15 cm, 300g por perfume
   3-4 productos:  20x15x15 cm, 300g por perfume
   5+ productos:   25x20x20 cm, 300g por perfume
   ```

3. **Opciones de envío:**
   - Envío a domicilio
   - Retiro en sucursal MercadoEnvíos
   - Punto de retiro

4. **Fallback:**
   - Si la API falla, se usa $2,500 como costo fijo
   - El usuario ve "Costo de envío estimado"

### Archivos Involucrados

- `app/Services/MercadoEnviosService.php` - Servicio principal
- `app/Http/Controllers/Web/CheckoutController.php` - Integración
- `config/services.php` - Configuración
- `routes/web.php` - Ruta AJAX para calcular envío
- `resources/views/checkout/payment.blade.php` - Vista con opciones

### API de MercadoEnvíos

#### Endpoint de Cotización
```
GET https://api.mercadolibre.com/shipments/options
```

**Parámetros:**
- `zip_code_from`: CP origen (ej: "1636")
- `zip_code_to`: CP destino (ej: "1425")
- `dimensions`: "15x10x10" (ancho x alto x profundidad en cm)
- `weight`: 300 (en gramos)
- `item_price`: 10000 (precio en pesos para calcular seguro)

**Respuesta:**
```json
{
  "options": [
    {
      "id": 123,
      "name": "Standard",
      "cost": 2800,
      "currency_id": "ARS",
      "estimated_delivery_time": {
        "date": "2026-02-10",
        "shipping": 3
      }
    }
  ]
}
```

### Testing

#### En Sandbox (Actual)
- Usa `MERCADOPAGO_SANDBOX=true`
- Las cotizaciones son aproximadas
- No se generan envíos reales

#### En Producción
1. Cambiar `MERCADOPAGO_SANDBOX=false`
2. Usar credenciales de producción
3. Verificar CP origen real de tu local/depósito

---

## Migración a Andreani (Futuro)

### ¿Por qué migrar?
- Tarifas corporativas más competitivas
- Mayor control sobre los envíos
- Pickup en sucursales Andreani
- Tracking más detallado

### Pasos para Migrar

#### 1. Obtener Credenciales
1. Crear cuenta en [Andreani Empresas](https://www.andreani.com/empresas)
2. Contratar servicio de e-commerce
3. Solicitar credenciales API:
   - Username
   - Password (o API Key)

#### 2. Agregar al .env
```env
ANDREANI_USERNAME=tu_usuario
ANDREANI_PASSWORD=tu_password
ANDREANI_ENVIRONMENT=testing  # o production
ANDREANI_CP_ORIGEN=1636
```

#### 3. Crear Servicio
Crear `app/Services/AndreaniService.php` basado en la estructura de `MercadoEnviosService.php`

**Endpoints principales:**
```
POST https://api.andreani.com/v2/tarifas - Cotizar
POST https://api.andreani.com/v2/ordenes-de-envio - Crear envío
GET https://api.andreani.com/v1/sucursales - Buscar sucursales
```

#### 4. Actualizar CheckoutController
Reemplazar llamadas a `MercadoEnviosService` por `AndreaniService`

#### 5. Testing
- Usar ambiente `testing` primero
- Verificar cotizaciones contra tarifario Andreani
- Probar creación de etiquetas

### Comparación

| Característica | MercadoEnvíos | Andreani |
|---------------|---------------|----------|
| Configuración | Fácil (usa mismo token MP) | Requiere cuenta empresas |
| Tarifas | Retail | Corporativas (negociables) |
| Cobertura | Nacional | Nacional |
| Puntos retiro | Red MercadoLibre | Sucursales Andreani |
| Tracking | Integrado con ML | API dedicada |
| Generación etiquetas | Automática | Manual/API |

### Recomendación
1. **Comenzar con MercadoEnvíos** (YA IMPLEMENTADO ✅)
   - Rápido de activar
   - Sin trámites adicionales
   - Ideal para validar el negocio

2. **Migrar a Andreani cuando:**
   - Tengas volumen constante (>20 envíos/mes)
   - Necesites mejores tarifas
   - Quieras más control sobre logística
   - Tengas cuenta empresarial configurada

---

## Personalización de Dimensiones

### Por Producto (Avanzado)
Si querés definir dimensiones específicas por producto:

1. **Agregar campos a la tabla products:**
```sql
ALTER TABLE products ADD COLUMN weight INT DEFAULT 300;
ALTER TABLE products ADD COLUMN width INT DEFAULT 15;
ALTER TABLE products ADD COLUMN height INT DEFAULT 10;
ALTER TABLE products ADD COLUMN length INT DEFAULT 10;
```

2. **Actualizar calculatePackageDimensions():**
```php
public function calculatePackageDimensions($cartItems): array
{
    $totalWeight = 0;
    $maxWidth = 0;
    $maxHeight = 0;
    $maxLength = 0;

    foreach ($cartItems as $item) {
        $product = $item->product;
        $totalWeight += ($product->weight ?? 300) * $item->quantity;
        $maxWidth = max($maxWidth, $product->width ?? 15);
        $maxHeight = max($maxHeight, $product->height ?? 10);
        $maxLength = max($maxLength, $product->length ?? 10);
    }

    return [
        'weight' => $totalWeight,
        'width' => $maxWidth,
        'height' => $maxHeight,
        'length' => $maxLength
    ];
}
```

---

## Troubleshooting

### Error: "No shipping options available"
- Verificar que `MERCADOPAGO_ACCESS_TOKEN` esté correcto
- Verificar que el CP destino sea válido (4 dígitos en Argentina)
- Revisar logs en `storage/logs/laravel.log`

### Error: "Shipping cost fallback to $2500"
- La API de MercadoEnvíos no está respondiendo
- Verificar conectividad a internet
- Revisar si las credenciales están vencidas

### Costos muy altos
- Verificar dimensiones del paquete (quizás están exageradas)
- Comparar con calculadora online de MercadoEnvíos
- Considerar optimizar packaging

---

## Próximos Pasos

- [x] Implementar MercadoEnvíos ✅
- [ ] Agregar selector de opciones de envío (domicilio vs sucursal)
- [ ] Mostrar mapa de puntos de retiro
- [ ] Generar etiqueta de envío post-compra
- [ ] Webhook para tracking de envío
- [ ] Migrar a Andreani cuando sea conveniente
