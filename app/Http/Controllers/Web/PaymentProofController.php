<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PaymentProofController extends Controller
{
    /**
     * Upload payment proof for an order
     */
    public function upload(Request $request, Order $order)
    {
        // Validate ownership
        if (!$this->canAccessOrder($order, $request)) {
            abort(403, 'No tienes permiso para acceder a este pedido.');
        }

        // Validate order payment method
        if ($order->payment_method !== 'transfer') {
            return back()->with('error', 'Este pedido no es por transferencia.');
        }

        // Validate payment not already confirmed
        if ($order->payment_confirmed_at) {
            return back()->with('info', 'El pago de este pedido ya fue confirmado.');
        }

        $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
        ], [
            'payment_proof.required' => 'Debes seleccionar un archivo.',
            'payment_proof.file' => 'El archivo no es válido.',
            'payment_proof.mimes' => 'El archivo debe ser JPG, PNG o PDF.',
            'payment_proof.max' => 'El archivo no debe superar los 5MB.',
        ]);

        // Delete old proof if exists
        if ($order->payment_proof) {
            Storage::disk('public')->delete($order->payment_proof);
        }

        // Store the file
        $file = $request->file('payment_proof');
        $filename = 'payment-proofs/' . $order->order_number . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('payment-proofs', $order->order_number . '_' . time() . '.' . $file->getClientOriginalExtension(), 'public');

        // Update order
        $order->update([
            'payment_proof' => $path,
            'payment_proof_uploaded_at' => now(),
        ]);

        return back()->with('success', '¡Comprobante subido exitosamente! Confirmaremos tu pago a la brevedad.');
    }

    /**
     * View payment proof
     */
    public function view(Order $order)
    {
        if (!$order->payment_proof) {
            abort(404, 'No hay comprobante de pago disponible.');
        }

        return response()->file(storage_path('app/public/' . $order->payment_proof));
    }

    /**
     * Check if user can access this order
     */
    private function canAccessOrder(Order $order, Request $request): bool
    {
        // If logged in, check user_id
        if (Auth::check() && $order->user_id === Auth::id()) {
            return true;
        }

        // If guest, check session token or email
        if (!Auth::check()) {
            // Check if order was just created (in session)
            if ($request->session()->has('last_order_id') && 
                $request->session()->get('last_order_id') === $order->id) {
                return true;
            }
            
            // Check email match (for guest orders)
            if ($order->guest_email && $request->session()->get('guest_email') === $order->guest_email) {
                return true;
            }
        }

        return false;
    }
}
