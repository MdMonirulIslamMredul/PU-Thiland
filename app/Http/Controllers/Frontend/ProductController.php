<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return view('frontend.products.index', [
            'products' => Product::where('status', true)->orderBy('sort_order')->latest()->paginate(9),
        ]);
    }

    public function show(Product $product)
    {
        abort_unless($product->status, 404);

        return view('frontend.products.show', [
            'product' => $product,
        ]);
    }
}
