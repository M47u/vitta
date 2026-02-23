<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'guest_email',
        'guest_name',
        'address_id',
        'status',
        'payment_status',
        'payment_method',
        'payment_proof',
        'payment_proof_uploaded_at',
        'payment_confirmed_at',
        'payment_reminder_sent_at',
        'subtotal',
        'discount',
        'shipping',
        'tax',
        'total',
        'coupon_code',
        'notes',
        'shipping_address',
        'paid_at',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'shipping_address' => 'array',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'payment_proof_uploaded_at' => 'datetime',
        'payment_confirmed_at' => 'datetime',
        'payment_reminder_sent_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'approved');
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pendiente',
            'processing' => 'Procesando',
            'paid' => 'Pagado',
            'shipped' => 'Enviado',
            'delivered' => 'Entregado',
            'cancelled' => 'Cancelado',
            'refunded' => 'Reembolsado',
            default => 'Desconocido',
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'pending' => 'Pendiente',
            'processing' => 'Procesando',
            'approved' => 'Aprobado',
            'rejected' => 'Rechazado',
            'cancelled' => 'Cancelado',
            'refunded' => 'Reembolsado',
            default => 'Desconocido',
        };
    }

    // Methods
    public static function generateOrderNumber(): string
    {
        do {
            $number = 'VP-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        } while (self::where('order_number', $number)->exists());

        return $number;
    }

    public function markAsPaid(): void
    {
        $this->update([
            'payment_status' => 'approved',
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Descontar stock de los productos vendidos
        $this->decrementStock();
    }

    /**
     * Decrement stock for all order items
     */
    private function decrementStock(): void
    {
        foreach ($this->items as $item) {
            if ($item->product_variant_id) {
                // Si es una variante, descontar stock de la variante
                $variant = ProductVariant::find($item->product_variant_id);
                if ($variant && $variant->stock >= $item->quantity) {
                    $variant->decrement('stock', $item->quantity);
                }
            } else {
                // Si no es variante, descontar del producto directamente (si tuviera stock)
                $product = Product::find($item->product_id);
                if ($product) {
                    // Los productos sin variantes no tienen stock en este sistema
                    // pero dejamos la lógica por si se implementa en el futuro
                }
            }
        }
    }

    public function markAsShipped(): void
    {
        $this->update([
            'status' => 'shipped',
            'shipped_at' => now(),
        ]);
    }

    public function markAsDelivered(): void
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
    }

    public function cancel(): void
    {
        // Solo restaurar stock si la orden estaba pagada
        $wasPaid = $this->payment_status === 'approved';

        $this->update([
            'status' => 'cancelled',
            'payment_status' => 'cancelled',
        ]);

        // Restaurar stock si la orden ya había sido pagada
        if ($wasPaid) {
            $this->restoreStock();
        }
    }

    /**
     * Restore stock for all order items (when order is cancelled or refunded)
     */
    private function restoreStock(): void
    {
        foreach ($this->items as $item) {
            if ($item->product_variant_id) {
                // Si es una variante, restaurar stock de la variante
                $variant = ProductVariant::find($item->product_variant_id);
                if ($variant) {
                    $variant->increment('stock', $item->quantity);
                }
            }
        }
    }
}