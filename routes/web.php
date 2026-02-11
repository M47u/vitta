<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\CustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ProductController as AdminProduct;
use App\Http\Controllers\Admin\OrderController as AdminOrder;
use App\Http\Controllers\Admin\SettingController;

// ===== RUTAS PÚBLICAS =====

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('productos')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{slug}', [ProductController::class, 'show'])->name('show');
});

Route::prefix('carrito')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/', [CartController::class, 'store'])->name('store');
    Route::patch('/{item}', [CartController::class, 'update'])->name('update');
    Route::delete('/{item}', [CartController::class, 'destroy'])->name('destroy');
});

// ===== RUTAS DE CHECKOUT (PÚBLICAS - GUEST CHECKOUT HABILITADO) =====

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Web\CheckoutController::class, 'index'])->name('index');
    Route::post('/address', [\App\Http\Controllers\Web\CheckoutController::class, 'storeAddress'])->name('address.store');
    Route::get('/payment/{address}', [\App\Http\Controllers\Web\CheckoutController::class, 'payment'])->name('payment');
    Route::get('/calculate-shipping/{address}', [\App\Http\Controllers\Web\CheckoutController::class, 'calculateShipping'])->name('calculate-shipping');
    Route::post('/process/{address}', [\App\Http\Controllers\Web\CheckoutController::class, 'processOrder'])->name('process');
    Route::get('/success/{order}', [\App\Http\Controllers\Web\CheckoutController::class, 'success'])->name('success');
    Route::get('/failure/{order}', [\App\Http\Controllers\Web\CheckoutController::class, 'failure'])->name('failure');
    Route::get('/pending/{order}', [\App\Http\Controllers\Web\CheckoutController::class, 'pending'])->name('pending');
});

// MercadoPago webhook (sin autenticación)
Route::post('/mercadopago/webhook', [\App\Http\Controllers\Web\CheckoutController::class, 'webhook'])->name('mercadopago.webhook');

// ===== RUTAS DE PERFIL (AUTENTICADAS) =====

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===== RUTAS DE CUSTOMER PANEL (AUTENTICADAS) =====

Route::prefix('customer')->name('customer.')->middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/', [CustomerController::class, 'dashboard'])->name('dashboard');
    
    // Orders
    Route::get('/orders', [CustomerController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [CustomerController::class, 'orderShow'])->name('orders.show');
    
    // Addresses
    Route::get('/addresses', [CustomerController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [CustomerController::class, 'addressStore'])->name('addresses.store');
    Route::put('/addresses/{address}', [CustomerController::class, 'addressUpdate'])->name('addresses.update');
    Route::delete('/addresses/{address}', [CustomerController::class, 'addressDestroy'])->name('addresses.destroy');
    
    // Account Settings
    Route::get('/account', [CustomerController::class, 'account'])->name('account');
    Route::put('/account', [CustomerController::class, 'accountUpdate'])->name('account.update');
    Route::put('/account/password', [CustomerController::class, 'passwordUpdate'])->name('account.password');
    
    // Wishlist
    Route::get('/wishlist', [CustomerController::class, 'wishlist'])->name('wishlist');
    Route::post('/wishlist/{product}/toggle', [CustomerController::class, 'wishlistToggle'])->name('wishlist.toggle');
});

// ===== RUTAS ADMIN (AUTENTICADAS + ADMIN) =====

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', AdminProduct::class);

    // Orders
    Route::get('orders', [AdminOrder::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrder::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [AdminOrder::class, 'updateStatus'])->name('orders.update-status');

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
});

// ===== INCLUIR RUTAS DE BREEZE =====
require __DIR__ . '/auth.php';