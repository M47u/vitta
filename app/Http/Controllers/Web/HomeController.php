<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Product::with(['category', 'variants'])
            ->where('is_featured', true)
            ->where('is_active', true)
            ->limit(8)
            ->get();

        $newArrivals = Product::with(['category', 'variants'])
            ->where('is_active', true)
            ->latest()
            ->limit(8)
            ->get();

        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->withCount('products')
            ->get();

        return view('home.index', compact('featured', 'newArrivals', 'categories'));
    }
}
