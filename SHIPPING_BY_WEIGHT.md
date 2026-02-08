# Sistema de Cálculo de Envío por Peso - Vitta Perfumes

## 📦 **Métodos de Cálculo Disponibles**

El sistema ahora soporta **3 métodos** de cálculo de envío que puedes configurar según tus necesidades:

### 1. **MercadoEnvíos (API)**
Utiliza la API oficial de MercadoLibre para calcular costos reales de envío basados en:
- Código postal origen/destino
- Peso del paquete
- Dimensiones del paquete
- Valor del producto (para seguro)

**Ventajas:**
- ✅ Costos reales y actualizados
- ✅ Múltiples opciones de envío (domicilio, sucursal, etc.)
- ✅ Tiempos de entrega estimados

**Desventajas:**
- ❌ Requiere configuración de API
- ❌ Depende de servicio externo

---

### 2. **Por Peso (Weight-Based)**
Calcula el costo basándose únicamente en el peso total del pedido.

**Fórmula:**
```
Si peso ≤ 500g:
    Costo = Costo Base ($1,500)

Si peso > 500g:
    kg_adicionales = ceil((peso - 500g) / 1000)
    Costo = Costo Base + (kg_adicionales × Costo por KG)
```

**Ejemplo:**
- **Pedido de 400g** → $1,500 (costo base)
- **Pedido de 800g** → $1,500 + (1 kg × $800) = **$2,300**
- **Pedido de 1.2kg** → $1,500 + (1 kg × $800) = **$2,300**
- **Pedido de 1.8kg** → $1,500 + (2 kg × $800) = **$3,100**

**Ventajas:**
- ✅ Simple y predecible
- ✅ No depende de servicios externos
- ✅ Fácil de explicar a clientes

**Desventajas:**
- ❌ No considera distancia
- ❌ Puede no reflejar costos reales

---

### 3. **Costo Fijo (Fixed)**
Aplica un costo único para todos los envíos, sin importar peso o dimensiones.

**Configuración:**
- Costo fijo: $2,500

**Ventajas:**
- ✅ Muy simple
- ✅ Predecible para el cliente

**Desventajas:**
- ❌ No refleja costos reales
- ❌ Puede no ser rentable

---

## ⚙️ **Configuración del Sistema**

### Variables de Entorno
```env
# MercadoPago/MercadoEnvíos
MERCADOPAGO_ACCESS_TOKEN=tu_access_token_aqui
MERCADOENVIOS_ZIP_CODE_FROM=1636
```

### Settings en Base de Datos

Puedes configurar estos valores desde el panel admin o directamente en la tabla `settings`:

```sql
-- Método de cálculo (mercadoenvios, weight, fixed)
UPDATE settings SET value = 'weight' WHERE key = 'shipping_method';

-- Costo base para envíos (hasta 500g)
UPDATE settings SET value = '1500' WHERE key = 'shipping_base_cost';

-- Costo por cada KG adicional
UPDATE settings SET value = '800' WHERE key = 'shipping_cost_per_kg';

-- Costo fijo (para método fixed)
UPDATE settings SET value = '2500' WHERE key = 'shipping_cost';

-- Mínimo para envío gratis
UPDATE settings SET value = '50000' WHERE key = 'free_shipping_minimum';
```

---

## 🔧 **Cómo Funciona el Sistema**

### 1. El cliente agrega productos al carrito
Cada producto tiene configurado:
- `package_weight` (en gramos)
- `package_width` (en cm)
- `package_height` (en cm)
- `package_length` (en cm)

### 2. Se calculan las dimensiones totales

```php
// En MercadoEnviosService::calculatePackageDimensions()

foreach ($cartItems as $item) {
    $weight = $product->package_weight ?? 250; // gramos
    $totalWeight += $weight * $item->quantity;
}
```

**Ejemplo de carrito:**
- 2× Perfume A (250g cada uno) = 500g
- 1× Perfume B (350g) = 350g
- **Total: 850g**

### 3. Se calcula el costo según el método configurado

#### **Si método = "mercadoenvios":**
```php
$result = $mercadoEnvios->calculateShipping($from, $to, $dimensions);

// Si falla → Fallback automático a cálculo por peso
if (!$result) {
    $cost = $this->calculateShippingByWeight($weight);
}
```

#### **Si método = "weight":**
```php
$cost = $this->calculateShippingByWeight($weight);

// 850g > 500g (base)
// kg_adicionales = ceil((850 - 500) / 1000) = 1
// costo = $1,500 + (1 × $800) = $2,300
```

#### **Si método = "fixed":**
```php
$cost = Setting::get('shipping_cost', 2500); // $2,500
```

### 4. Se verifica envío gratis
```php
if ($cart->total >= $freeShippingMin) {
    return ['cost' => 0, 'method' => 'free'];
}
```

---

## 📊 **Tabla de Costos por Peso (Configuración Actual)**

| Peso Total | KG Adicionales | Cálculo | Costo Final |
|------------|----------------|---------|-------------|
| 0 - 500g | 0 | $1,500 + ($0 × $800) | **$1,500** |
| 501g - 1kg | 1 | $1,500 + ($1 × $800) | **$2,300** |
| 1kg - 2kg | 1-2 | $1,500 + ($1-2 × $800) | **$2,300 - $3,100** |
| 2kg - 3kg | 2-3 | $1,500 + ($2-3 × $800) | **$3,100 - $3,900** |
| 3kg - 4kg | 3-4 | $1,500 + ($3-4 × $800) | **$3,900 - $4,700** |

---

## 🎯 **Configurar Dimensiones de Productos**

### Opción 1: Valores por Defecto (Ya configurados)
Todos los productos tienen valores por defecto después de la migración:
- **Peso:** 250g
- **Dimensiones:** 8×12×8 cm

### Opción 2: Actualizar Manualmente

```sql
-- Actualizar un producto específico
UPDATE products 
SET 
    package_weight = 350,  -- 350 gramos
    package_width = 10,
    package_height = 15,
    package_length = 10
WHERE sku = 'PERF-001';

-- Actualizar por categoría (ejemplo: perfumes grandes)
UPDATE products 
SET package_weight = 400 
WHERE category_id = 2;
```

### Opción 3: Desde el Panel Admin
Al crear/editar productos, configurar:
- **Peso del paquete:** Peso con empaque incluido (gramos)
- **Ancho, Alto, Largo:** Dimensiones de la caja (cm)

---

## 🧪 **Probar el Sistema**

### 1. Cambiar método de cálculo
```sql
-- Probar cálculo por peso
UPDATE settings SET value = 'weight' WHERE key = 'shipping_method';

-- Probar MercadoEnvíos
UPDATE settings SET value = 'mercadoenvios' WHERE key = 'shipping_method';

-- Probar costo fijo
UPDATE settings SET value = 'fixed' WHERE key = 'shipping_method';
```

### 2. Ajustar costos
```sql
-- Aumentar costo base
UPDATE settings SET value = '2000' WHERE key = 'shipping_base_cost';

-- Aumentar costo por kg
UPDATE settings SET value = '1000' WHERE key = 'shipping_cost_per_kg';
```

### 3. Ver logs en tiempo real
```bash
tail -f storage/logs/laravel.log
```

**Buscar:**
```
[INFO] Dimensiones calculadas del paquete
[INFO] Calculando envío por peso
[INFO] Costo de envío calculado por peso
```

---

## 📋 **Checklist de Implementación**

- [x] Migración de campos de peso/dimensiones ejecutada
- [x] Productos actualizados con valores por defecto
- [x] Settings de configuración agregados
- [x] Método `calculateShippingByWeight()` implementado
- [x] Método `calculateShippingCost()` con selector automático
- [x] CheckoutController actualizado
- [x] Logs de debugging agregados
- [ ] Configurar método preferido: `UPDATE settings SET value = 'weight' WHERE key = 'shipping_method';`
- [ ] Ajustar costos según tu logística
- [ ] Probar checkout completo

---

## 🔍 **Troubleshooting**

### Problema: Siempre usa fallback de $2,500
**Solución:**
1. Verificar que el setting `shipping_method` exista:
```sql
SELECT * FROM settings WHERE key = 'shipping_method';
```

2. Si no existe, crearlo:
```bash
php artisan db:seed --class=SettingSeeder
```

### Problema: Cálculo por peso no funciona
**Solución:**
1. Verificar que los productos tengan peso:
```sql
SELECT id, name, package_weight FROM products LIMIT 10;
```

2. Actualizar productos sin peso:
```bash
php artisan db:seed --class=UpdateProductDimensionsSeeder
```

### Problema: MercadoEnvíos siempre falla
**Solución:**
1. Verificar token configurado: `.env` → `MERCADOPAGO_ACCESS_TOKEN`
2. Verificar código postal origen: `.env` → `MERCADOENVIOS_ZIP_CODE_FROM`
3. Ver logs para detalles del error

---

## 📚 **API del Servicio**

### Método Principal
```php
$mercadoEnvios = new MercadoEnviosService();

$result = $mercadoEnvios->calculateShippingCost(
    zipCodeFrom: '1636',
    zipCodeTo: '5000',
    dimensions: [
        'weight' => 850,        // gramos
        'width' => 15,          // cm
        'height' => 20,         // cm
        'length' => 25,         // cm
        'item_price' => 45000   // ARS
    ]
);

// Respuesta:
[
    'cost' => 2300,              // Costo en ARS
    'method' => 'weight',        // Método usado
    'details' => [...]           // Detalles adicionales
]
```

### Métodos Disponibles
```php
// Calcular solo por peso
$cost = $mercadoEnvios->calculateShippingByWeight(850); // $2,300

// Calcular dimensiones del carrito
$dimensions = $mercadoEnvios->calculatePackageDimensions($cartItems);

// Obtener opciones de MercadoEnvíos
$options = $mercadoEnvios->getShippingOptions($from, $to, $dimensions);
```

---

## 💡 **Recomendaciones**

1. **Empezar con método "weight"**: Es simple, predecible y no depende de APIs externas

2. **Calibrar costos**: Analiza tus envíos reales y ajusta `shipping_base_cost` y `shipping_cost_per_kg`

3. **Usar MercadoEnvíos en producción**: Una vez configurado, ofrece la mejor experiencia al cliente

4. **Configurar pesos reales**: Pesa tus productos con empaque y actualiza la base de datos

5. **Monitorear logs**: Los primeros días, revisa los logs para detectar problemas

---

## 🎉 **Ejemplo Completo de Uso**

```php
// Carrito del cliente
Producto A: Perfume 100ml (250g) × 2 = 500g
Producto B: Perfume 150ml (350g) × 1 = 350g
Total peso: 850g
Subtotal: $40,000

// Configuración actual
shipping_method = 'weight'
shipping_base_cost = 1500  (hasta 500g)
shipping_cost_per_kg = 800
free_shipping_minimum = 50000

// Cálculo
850g > 500g (base)
kg_adicionales = ceil((850 - 500) / 1000) = 1
costo_envio = 1500 + (1 × 800) = $2,300

// Total a pagar
$40,000 (productos) + $2,300 (envío) = $42,300

// Si el subtotal fuera ≥ $50,000 → Envío GRATIS
```

¡El sistema ahora calcula envíos de forma inteligente basándose en el peso real de tus productos! 🚀
