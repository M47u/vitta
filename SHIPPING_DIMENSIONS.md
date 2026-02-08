# Configuración de Dimensiones para Envío - MercadoEnvíos

## 🔧 Problemas Corregidos

### 1. Faltaban Campos de Dimensiones en Productos
**Problema:** Los productos no tenían campos para almacenar peso y dimensiones, entonces el sistema usaba valores genéricos estimados que no eran precisos.

**Solución:** Agregué 4 nuevos campos a la tabla `products`:
- `package_width` (cm) - Ancho del paquete
- `package_height` (cm) - Alto del paquete  
- `package_length` (cm) - Largo del paquete
- `package_weight` (gramos) - Peso con packaging

**Valores por defecto:**
- Ancho: 8 cm
- Alto: 12 cm
- Largo: 8 cm
- Peso: 250 gramos

### 2. Bug en el Servicio MercadoEnvíos
**Problema:** El método `calculateShipping()` sobrescribía los valores calculados con defaults usando el operador `??`:

```php
// ❌ ANTES (incorrecto)
'width' => $dimensions['width'] ?? 15,  // Siempre usaba 15 si width existía como 0
```

**Solución:** Eliminé los operadores null coalescing y ahora usa directamente los valores calculados:

```php
// ✅ AHORA (correcto)
'width' => $dimensions['width'],  // Usa el valor real calculado
```

### 3. Logging Mejorado
Agregué logs detallados para debuggear problemas con la API:
- Request enviado a MercadoEnvíos
- Response recibido
- Dimensiones calculadas del paquete
- Errores con stack trace completo

---

## 📦 Cómo Configurar Dimensiones de Productos

### Opción 1: Por el Admin Panel (Recomendado)
Cuando crees o edites un producto, agrega estos campos:

```
Dimensiones del Paquete:
├─ Ancho: 8 cm (ej: botella de perfume típica)
├─ Alto: 12 cm (ej: botella + caja)
├─ Largo: 8 cm
└─ Peso: 250 gramos (incluye caja y protección)
```

### Opción 2: Manualmente en la Base de Datos

```sql
-- Actualizar un producto específico
UPDATE products 
SET 
    package_width = 8,
    package_height = 12,
    package_length = 8,
    package_weight = 250
WHERE id = 1;

-- Actualizar todos los productos con valores estándar
UPDATE products 
SET 
    package_width = 8,
    package_height = 12,
    package_length = 8,
    package_weight = 250;
```

### Opción 3: Mediante Seeder

Crea un seeder para actualizar productos existentes:

```php
// database/seeders/UpdateProductDimensionsSeeder.php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class UpdateProductDimensionsSeeder extends Seeder
{
    public function run(): void
    {
        // Actualizar todos los productos con dimensiones estándar
        Product::query()->update([
            'package_width' => 8,
            'package_height' => 12,
            'package_length' => 8,
            'package_weight' => 250,
        ]);
    }
}
```

Ejecutar:
```bash
php artisan db:seed --class=UpdateProductDimensionsSeeder
```

---

## 🧮 Cómo Funciona el Cálculo de Dimensiones

El método `calculatePackageDimensions()` ahora:

1. **Lee las dimensiones reales** de cada producto en el carrito
2. **Acumula el peso total** (suma peso × cantidad de cada producto)
3. **Calcula dimensiones del paquete**:
   - **Ancho**: Toma el máximo entre todos los productos
   - **Alto**: Toma el máximo entre todos los productos
   - **Largo**: Suma el largo de cada producto × cantidad
4. **Aplica ajustes** si hay muchos items (agranda el paquete)
5. **Respeta límites** de MercadoEnvíos (máx 100cm de largo)

### Ejemplo de Cálculo

**Carrito:**
- 2x Perfume A (8×12×8 cm, 250g)
- 1x Perfume B (10×15×10 cm, 300g)

**Resultado:**
```php
[
    'weight' => 800,      // (250×2) + (300×1) = 800g
    'width' => 10,        // max(8, 10) = 10cm
    'height' => 15,       // max(12, 15) = 15cm
    'length' => 26        // (8×2) + (10×1) = 26cm
]
```

---

## 🔍 Debuggear Problemas de Envío

Si sigue usando el fallback de $2500, revisa los logs:

```bash
# Ver logs en tiempo real
tail -f storage/logs/laravel.log
```

**Busca estas entradas:**

```
[YYYY-MM-DD HH:MM:SS] local.INFO: Dimensiones calculadas del paquete
{"items_count": 2, "dimensions": {"weight": 500, "width": 8, "height": 12, "length": 16}}

[YYYY-MM-DD HH:MM:SS] local.INFO: MercadoEnvíos Request
{"url": "...", "payload": {"zip_code_from": "1636", "zip_code_to": "5000", ...}}

[YYYY-MM-DD HH:MM:SS] local.INFO: MercadoEnvíos Response
{"data": {"options": [...]}}
```

**Errores comunes:**

1. **Access Token no configurado**
   ```
   MercadoEnvíos: Access token no configurado
   ```
   Solución: Verificar `.env` tiene `MERCADOPAGO_ACCESS_TOKEN`

2. **Código postal inválido**
   ```
   MercadoEnvíos API Error
   {"status": 400, "body": "invalid zip code"}
   ```
   Solución: Verificar que `MERCADOENVIOS_ZIP_CODE_FROM` es válido

3. **Dimensiones fuera de rango**
   ```
   MercadoEnvíos API Error
   {"status": 400, "body": "dimensions out of range"}
   ```
   Solución: Revisar que las dimensiones de productos no sean excesivas

---

## 📋 Checklist de Configuración

- [x] Migración ejecutada (`php artisan migrate`)
- [ ] Variables de entorno configuradas:
  - [ ] `MERCADOPAGO_ACCESS_TOKEN`
  - [ ] `MERCADOENVIOS_ZIP_CODE_FROM` (tu código postal de origen)
- [ ] Dimensiones configuradas en productos
- [ ] Probar checkout y verificar logs

---

## 🎯 Dimensiones Recomendadas por Tipo de Perfume

| Tipo | Volumen | Ancho | Alto | Largo | Peso |
|------|---------|-------|------|-------|------|
| Muestra | 5-10 ml | 5 cm | 8 cm | 5 cm | 100g |
| Eau de Toilette | 50 ml | 6 cm | 10 cm | 6 cm | 200g |
| Eau de Parfum | 100 ml | 8 cm | 12 cm | 8 cm | 250g |
| Perfume Grande | 150 ml | 10 cm | 15 cm | 10 cm | 350g |

---

## 📚 Referencias

- [Documentación MercadoEnvíos API](https://developers.mercadolibre.com.ar/es_ar/envios-de-mercado-envios)
- [Límites de dimensiones MercadoEnvíos](https://www.mercadolibre.com.ar/ayuda/M_didas-y-pesos-para-env_os_2404)
