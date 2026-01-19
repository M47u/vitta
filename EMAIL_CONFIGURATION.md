# Configuración de Email - Vitta Perfumes

## Sistema de Notificaciones por Email Implementado ✅

Se ha implementado un sistema completo de notificaciones por email para mejorar la experiencia del usuario y cumplir con requisitos legales de e-commerce.

---

## 📧 Tipos de Emails Implementados

### 1. **Email de Confirmación de Pedido** (`OrderConfirmation`)
- **Cuándo se envía**: Automáticamente cuando el pago es aprobado por MercadoPago
- **Contenido**:
  - Número de pedido
  - Fecha de compra
  - Lista completa de productos con variantes y precios
  - Desglose de costos (subtotal, envío, total)
  - Dirección de envío completa
  - Botón para ver detalles del pedido

### 2. **Email de Cambio de Estado** (`OrderStatusChanged`)
- **Cuándo se envía**: Cuando el administrador actualiza el estado de un pedido
- **Contenido**:
  - Estado actual con icono visual (⏳ Pendiente, ⚙️ En Proceso, 📦 Enviado, ✅ Entregado, ❌ Cancelado)
  - Alertas especiales según el estado:
    - **Enviado**: Mensaje con código de seguimiento
    - **Entregado**: Invitación a dejar reseña
    - **Cancelado**: Información de contacto
  - Resumen del pedido

### 3. **Email de Bienvenida** (`WelcomeEmail`)
- **Cuándo se envía**: Cuando un nuevo usuario se registra
- **Contenido**:
  - Mensaje de bienvenida personalizado
  - Características principales de la cuenta
  - Información de envío gratuito
  - Botón para comenzar a comprar

---

## 🛠️ Configuración Técnica

### Archivos Creados

**Clases Mailable:**
- `app/Mail/OrderConfirmation.php`
- `app/Mail/OrderStatusChanged.php`
- `app/Mail/WelcomeEmail.php`

**Vistas de Email:**
- `resources/views/emails/layout.blade.php` (plantilla base)
- `resources/views/emails/order-confirmation.blade.php`
- `resources/views/emails/order-status-changed.blade.php`
- `resources/views/emails/welcome.blade.php`

**Controladores Modificados:**
- `app/Http/Controllers/Web/CheckoutController.php` (línea ~350)
- `app/Http/Controllers/Admin/OrderController.php` (líneas ~62 y ~77)
- `app/Http/Controllers/Auth/RegisteredUserController.php` (línea ~42)

---

## ⚙️ Configuración de Variables de Entorno

### Desarrollo Local (Mailtrap)

Para pruebas sin enviar emails reales, usa [Mailtrap](https://mailtrap.io):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_username_de_mailtrap
MAIL_PASSWORD=tu_password_de_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@vittaperfumes.com"
MAIL_FROM_NAME="Vitta Perfumes"
```

**Pasos para configurar Mailtrap:**
1. Crear cuenta gratuita en https://mailtrap.io
2. Ir a "Email Testing" > "Inboxes"
3. Copiar credenciales SMTP
4. Pegar en `.env`

### Producción (Gmail)

Para enviar emails reales usando Gmail:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@vittaperfumes.com"
MAIL_FROM_NAME="Vitta Perfumes"
```

**Pasos para configurar Gmail:**
1. Ir a tu cuenta de Google
2. Habilitar verificación en 2 pasos
3. Generar contraseña de aplicación: https://myaccount.google.com/apppasswords
4. Usar esa contraseña en `MAIL_PASSWORD`

### Producción (SendGrid, AWS SES, etc.)

Para servicios profesionales de email:

**SendGrid:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=tu_api_key_de_sendgrid
MAIL_ENCRYPTION=tls
```

**AWS SES:**
```env
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=tu_access_key
AWS_SECRET_ACCESS_KEY=tu_secret_key
AWS_DEFAULT_REGION=us-east-1
MAIL_FROM_ADDRESS="noreply@vittaperfumes.com"
```

---

## 🎨 Diseño de Emails

Los emails utilizan el diseño corporativo de Vitta Perfumes:

- **Colores**:
  - Oro: `#D4AF37`
  - Negro: `#0A0A0A`, `#1A1A1A`
  - Perla: `#F8F5F0`
  
- **Características**:
  - Diseño responsive (max-width 600px)
  - CSS inline para compatibilidad con clientes de email
  - Header con degradado dorado
  - Tipografía elegante (system fonts)
  - Botones con estilo consistente

---

## ✅ Testing de Emails

### Probar en Desarrollo

1. Configurar Mailtrap en `.env`
2. Ejecutar una acción que envíe email:
   - Registrar nuevo usuario → Email de bienvenida
   - Completar compra → Email de confirmación (cuando MercadoPago apruebe)
   - Cambiar estado de orden → Email de cambio de estado
3. Ver email en bandeja de Mailtrap

### Verificar Envío Manual

```bash
php artisan tinker
```

```php
// Probar email de bienvenida
$user = \App\Models\User::first();
Mail::to('test@example.com')->send(new \App\Mail\WelcomeEmail($user));

// Probar email de confirmación
$order = \App\Models\Order::with(['items.product', 'user', 'address'])->first();
Mail::to('test@example.com')->send(new \App\Mail\OrderConfirmation($order));

// Probar email de cambio de estado
Mail::to('test@example.com')->send(new \App\Mail\OrderStatusChanged($order, 'pending'));
```

---

## 📊 Mejoras Futuras (Opcional)

### Envío Asíncrono con Colas

Para mejorar performance, enviar emails en segundo plano:

1. Configurar driver de cola en `.env`:
```env
QUEUE_CONNECTION=database
```

2. Cambiar `Mail::to()->send()` por `Mail::to()->queue()`:
```php
Mail::to($user->email)->queue(new WelcomeEmail($user));
```

3. Ejecutar worker:
```bash
php artisan queue:work
```

### Personalización Avanzada

- **Adjuntar PDF** de factura en confirmación de pedido
- **Emails transaccionales adicionales**:
  - Recordatorio de carrito abandonado
  - Confirmación de envío con tracking
  - Solicitud de reseña post-entrega
  - Newsletter de nuevos productos
  
### Analytics

- Integrar seguimiento de aperturas (pixel de tracking)
- Seguimiento de clics en botones (UTM parameters)
- Reportes de deliverability

---

## 🔒 Seguridad y Buenas Prácticas

✅ **Implementado:**
- Try-catch en envío de emails (no rompe flujo si falla)
- Logging de errores de email
- Validación de datos antes de enviar
- FROM address corporativo

⚠️ **Recomendaciones:**
- Usar DKIM, SPF, DMARC en dominio de producción
- Rate limiting para evitar spam
- Validar emails antes de enviar (verificación de dominio)
- Mantener lista de emails rebotados (bounces)

---

## 📝 Checklist de Implementación

- [x] Crear clases Mailable (3)
- [x] Crear vistas de email (4 archivos)
- [x] Integrar en CheckoutController (webhook MercadoPago)
- [x] Integrar en OrderController (cambio de estado + cancelación)
- [x] Integrar en RegisteredUserController (bienvenida)
- [x] Configurar variables de entorno (.env y .env.example)
- [x] Documentar configuración y uso
- [ ] **Próximo paso**: Configurar credenciales reales en `.env` (Mailtrap o Gmail)
- [ ] **Próximo paso**: Probar envío de cada tipo de email
- [ ] **Próximo paso**: Verificar diseño en diferentes clientes (Gmail, Outlook, etc.)

---

## 🆘 Troubleshooting

### El email no se envía

1. Verificar configuración en `.env`
2. Verificar logs: `storage/logs/laravel.log`
3. Probar conexión SMTP:
```bash
php artisan tinker
```
```php
Mail::raw('Test email', function($msg) {
    $msg->to('test@example.com')->subject('Test');
});
```

### Email llega a spam

- Verificar configuración SPF/DKIM del dominio
- Usar servicio profesional (SendGrid, AWS SES)
- Evitar palabras spam en asunto ("gratis", "descuento", etc.)
- Incluir link de unsubscribe

### Diseño se ve mal en Outlook

- Outlook usa motor de renderizado de Word
- Verificar que CSS está inline
- Evitar flexbox/grid, usar tablas
- Probar en https://www.emailonacid.com/ o https://litmus.com/

---

**Implementado por**: GitHub Copilot  
**Fecha**: Diciembre 2024  
**Versión Laravel**: 12.40.2
