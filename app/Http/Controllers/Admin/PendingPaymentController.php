<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PaymentReminder;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PendingPaymentController extends Controller
{
    /**
     * Display pending payments dashboard
     */
    public function index(Request $request)
    {
        // Get orders with transfer payment method, not confirmed
        $query = Order::where('payment_method', 'transfer')
            ->whereNull('payment_confirmed_at')
            ->with(['user', 'items']);

        // Search by order number
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('guest_name', 'like', "%{$search}%")
                  ->orWhere('guest_email', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $pendingPayments = $query->orderBy('created_at', 'desc')->get();

        // Separate orders by status
        $withProof = $pendingPayments->filter(fn($order) => $order->payment_proof !== null);
        $withoutProof = $pendingPayments->filter(fn($order) => $order->payment_proof === null);
        
        // Get orders older than 24 hours without proof
        $needsReminder = $withoutProof->filter(function($order) {
            return $order->created_at->diffInHours(now()) >= 2 && 
                   !$order->payment_reminder_sent_at;
        });

        // Statistics
        $stats = [
            'total_pending' => $pendingPayments->count(),
            'with_proof' => $withProof->count(),
            'without_proof' => $withoutProof->count(),
            'needs_reminder' => $needsReminder->count(),
            'total_amount' => $pendingPayments->sum('total'),
        ];

        return view('admin.payments.pending', compact('pendingPayments', 'withProof', 'withoutProof', 'needsReminder', 'stats'));
    }

    /**
     * Confirm payment for an order
     */
    public function confirm(Order $order)
    {
        if ($order->payment_confirmed_at) {
            return back()->with('info', 'Este pago ya fue confirmado anteriormente.');
        }

        $order->update([
            'payment_confirmed_at' => now(),
            'payment_status' => 'approved',
            'paid_at' => now(),
            'status' => 'processing', // Move to processing
        ]);

        // TODO: Send confirmation email to customer

        return back()->with('success', "Pago confirmado para pedido #{$order->order_number}");
    }

    /**
     * Send reminder email
     */
    public function sendReminder(Order $order)
    {
        if ($order->payment_confirmed_at) {
            return back()->with('info', 'Este pago ya fue confirmado.');
        }

        if ($order->payment_proof) {
            return back()->with('info', 'El cliente ya subió el comprobante.');
        }

        $email = $order->user ? $order->user->email : $order->guest_email;

        if (!$email) {
            return back()->with('error', 'No hay email asociado a este pedido.');
        }

        try {
            Mail::to($email)->send(new PaymentReminder($order));

            $order->update([
                'payment_reminder_sent_at' => now(),
            ]);

            return back()->with('success', "Recordatorio enviado a {$email} para pedido #{$order->order_number}");
        } catch (\Exception $e) {
            return back()->with('error', "Error al enviar email: " . $e->getMessage());
        }
    }

    /**
     * Reject payment
     */
    public function reject(Order $order, Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $order->update([
            'payment_status' => 'rejected',
            'status' => 'cancelled',
            'notes' => ($order->notes ? $order->notes . "\n\n" : '') . "Pago rechazado: " . $request->reason,
        ]);

        // TODO: Send rejection email with reason

        return back()->with('success', "Pago rechazado para pedido #{$order->order_number}");
    }
}
