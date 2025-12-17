<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $cartCount = 0;

            if (Auth::check()) {
                $cart = Cart::where('user_id', Auth::id())->first();
            } else {
                $sessionId = Session::getId();
                $cart = Cart::where('session_id', $sessionId)->first();
            }

            if ($cart) {
                $cartCount = $cart->getItemsCount();
            }

            $view->with('cartCount', $cartCount);
        });
    }
}