<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Mail\OrderStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product']);

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('order_number', 'like', "%{$search}%")
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'items.productVariant']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:255',
        ]);

        // Guardar estado anterior
        $previousStatus = $order->status;

        // Actualizar orden
        $order->update($validated);

        // Enviar email de cambio de estado solo si el estado cambió
        if ($previousStatus !== $validated['status']) {
            try {
                Mail::to($order->user->email)->send(new OrderStatusChanged($order, $previousStatus));
            } catch (\Exception $e) {
                Log::error('Error sending order status email: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Estado de la orden actualizado exitosamente');
    }

    /**
     * Cancel an order.
     */
    public function cancel(Order $order)
    {
        if (in_array($order->status, ['shipped', 'delivered'])) {
            return redirect()->back()
                ->with('error', 'No se puede cancelar una orden que ya fue enviada o entregada');
        }

        $previousStatus = $order->status;
        $order->update(['status' => 'cancelled']);

        // Enviar email de cancelación
        try {
            Mail::to($order->user->email)->send(new OrderStatusChanged($order, $previousStatus));
        } catch (\Exception $e) {
            Log::error('Error sending cancellation email: ' . $e->getMessage());
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Orden cancelada exitosamente');
    }

    /**
     * Delete an order (soft delete recommended).
     */
    public function destroy(Order $order)
    {
        // Only allow deletion of cancelled orders
        if ($order->status !== 'cancelled') {
            return redirect()->back()
                ->with('error', 'Solo se pueden eliminar órdenes canceladas');
        }

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Orden eliminada exitosamente');
    }
}
