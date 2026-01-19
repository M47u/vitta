@extends('emails.layout')

@section('title', '¡Bienvenido a Vitta Perfumes!')

@section('content')
    <h2>¡Bienvenido a Vitta Perfumes! 🌟</h2>
    
    <p>Hola <strong>{{ $user->name }}</strong>,</p>
    
    <p>Nos complace darte la bienvenida a <strong>Vitta Perfumes</strong>, donde el lujo árabe cobra vida a través de fragancias excepcionales.</p>

    <div style="background: rgba(212, 175, 55, 0.1); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 8px; padding: 25px; margin: 30px 0; text-align: center;">
        <p style="font-size: 18px; color: #D4AF37; margin: 0 0 10px; font-weight: 600;">
            ✨ Tu cuenta ha sido creada exitosamente
        </p>
        <p style="margin: 0; font-size: 14px; color: rgba(248, 245, 240, 0.8);">
            Ya puedes explorar nuestra colección exclusiva de perfumes árabes
        </p>
    </div>

    <div class="divider"></div>

    <h3 style="color: #D4AF37; margin-bottom: 20px; text-align: center;">¿Qué puedes hacer ahora?</h3>

    <div style="display: grid; grid-template-columns: 1fr; gap: 15px; margin: 30px 0;">
        
        <div style="background: #0A0A0A; border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 20px;">
            <p style="font-size: 18px; margin: 0 0 8px;">🛍️ <strong>Explorar Catálogo</strong></p>
            <p style="margin: 0; font-size: 14px; color: rgba(248, 245, 240, 0.7);">
                Descubre nuestra exclusiva colección de fragancias árabes de lujo
            </p>
        </div>

        <div style="background: #0A0A0A; border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 20px;">
            <p style="font-size: 18px; margin: 0 0 8px;">❤️ <strong>Crear tu Lista de Favoritos</strong></p>
            <p style="margin: 0; font-size: 14px; color: rgba(248, 245, 240, 0.7);">
                Guarda tus fragancias preferidas para comprarlas más tarde
            </p>
        </div>

        <div style="background: #0A0A0A; border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 20px;">
            <p style="font-size: 18px; margin: 0 0 8px;">📦 <strong>Seguimiento de Pedidos</strong></p>
            <p style="margin: 0; font-size: 14px; color: rgba(248, 245, 240, 0.7);">
                Gestiona tus compras y direcciones desde tu panel personal
            </p>
        </div>

    </div>

    <div style="text-align: center; margin: 40px 0;">
        <a href="{{ route('home') }}" class="button">
            Comenzar a Comprar
        </a>
    </div>

    <div class="divider"></div>

    <div style="background: rgba(212, 175, 55, 0.05); border-radius: 8px; padding: 25px; margin: 30px 0;">
        <h3 style="color: #D4AF37; margin: 0 0 15px; text-align: center;">🚚 Envío Gratis</h3>
        <p style="text-align: center; margin: 0; font-size: 14px; color: rgba(248, 245, 240, 0.8);">
            En compras mayores a <strong style="color: #D4AF37;">${{ number_format(\App\Models\Setting::get('shipping.free_shipping_minimum', 50000), 0, ',', '.') }}</strong>
        </p>
    </div>

    <p style="color: rgba(248, 245, 240, 0.7); font-size: 13px; text-align: center;">
        Gracias por elegirnos. Estamos aquí para brindarte la mejor experiencia en fragancias de lujo.
    </p>

    <p style="text-align: center; margin-top: 30px;">
        <strong style="color: #D4AF37;">El equipo de Vitta Perfumes</strong>
    </p>
@endsection
