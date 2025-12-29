<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Address;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Show customer dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get recent orders
        $recentOrders = Order::where('user_id', $user->id)
            ->with(['items.product'])
            ->latest()
            ->take(5)
            ->get();
        
        // Get stats
        $stats = [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'pending_orders' => Order::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'processing'])
                ->count(),
            'total_spent' => Order::where('user_id', $user->id)
                ->where('payment_status', 'paid')
                ->sum('total'),
            'wishlist_count' => $user->wishlist()->count(),
        ];
        
        return view('customer.dashboard', compact('user', 'recentOrders', 'stats'));
    }

    /**
     * Show all customer orders
     */
    public function orders(Request $request)
    {
        $query = Order::where('user_id', Auth::id())
            ->with(['items.product', 'items.variant']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Search by order number
        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }
        
        $orders = $query->latest()->paginate(10);
        
        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Show single order detail
     */
    public function orderShow($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->with(['items.product', 'items.variant', 'address', 'transactions'])
            ->findOrFail($id);
        
        return view('customer.orders.show', compact('order'));
    }

    /**
     * Show customer addresses
     */
    public function addresses()
    {
        $addresses = Address::where('user_id', Auth::id())
            ->orderBy('is_default', 'desc')
            ->get();
        
        return view('customer.addresses.index', compact('addresses'));
    }

    /**
     * Create new address
     */
    public function addressStore(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'street_address' => 'required|string|max:255',
            'street_number' => 'required|string|max:10',
            'apartment' => 'nullable|string|max:50',
            'neighborhood' => 'nullable|string|max:100',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'is_default' => 'boolean',
        ]);
        
        $validated['user_id'] = Auth::id();
        
        // If this is the first address or marked as default, set it as default
        if ($request->is_default || Address::where('user_id', Auth::id())->count() === 0) {
            // Remove default from all other addresses
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
            $validated['is_default'] = true;
        }
        
        Address::create($validated);
        
        return redirect()->route('customer.addresses')
            ->with('success', 'Dirección agregada correctamente');
    }

    /**
     * Update address
     */
    public function addressUpdate(Request $request, $id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        
        // If only updating is_default flag
        if ($request->has('is_default') && $request->is_default) {
            // Remove default from all addresses
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
            
            // Set this one as default
            $address->update(['is_default' => true]);
            
            return redirect()->route('customer.addresses')
                ->with('success', 'Dirección predeterminada actualizada');
        }
        
        // Full update
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'street_address' => 'required|string|max:255',
            'street_number' => 'required|string|max:10',
            'apartment' => 'nullable|string|max:50',
            'neighborhood' => 'nullable|string|max:100',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'is_default' => 'boolean',
        ]);
        
        if ($request->is_default) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
            $validated['is_default'] = true;
        }
        
        $address->update($validated);
        
        return redirect()->route('customer.addresses')
            ->with('success', 'Dirección actualizada correctamente');
    }

    /**
     * Delete address
     */
    public function addressDestroy($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        
        if ($address->is_default) {
            return redirect()->back()
                ->with('error', 'No puedes eliminar la dirección predeterminada');
        }
        
        $address->delete();
        
        return redirect()->route('customer.addresses')
            ->with('success', 'Dirección eliminada correctamente');
    }

    /**
     * Show account settings
     */
    public function account()
    {
        $user = Auth::user();
        
        return view('customer.account', compact('user'));
    }

    /**
     * Update account info
     */
    public function accountUpdate(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);
        
        $user->update($validated);
        
        return redirect()->route('customer.account')
            ->with('success', 'Datos actualizados correctamente');
    }

    /**
     * Update password
     */
    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta']);
        }
        
        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        
        return redirect()->route('customer.account')
            ->with('success', 'Contraseña actualizada correctamente');
    }

    /**
     * Show wishlist
     */
    public function wishlist()
    {
        $user = Auth::user();
        $products = $user->wishlist()->with('category', 'variants')->get();
        
        return view('customer.wishlist', compact('products'));
    }

    /**
     * Toggle product in wishlist
     */
    public function wishlistToggle($productId)
    {
        $user = Auth::user();
        $product = Product::findOrFail($productId);
        
        if ($user->wishlist()->where('product_id', $productId)->exists()) {
            $user->wishlist()->detach($productId);
            $message = 'Producto eliminado de favoritos';
        } else {
            $user->wishlist()->attach($productId);
            $message = 'Producto agregado a favoritos';
        }
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'in_wishlist' => $user->wishlist()->where('product_id', $productId)->exists(),
        ]);
    }
}
