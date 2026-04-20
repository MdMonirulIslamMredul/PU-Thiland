<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', ['items' => []]);
        $items = collect($cart['items'])->values();
        $total = $items->sum(fn($item) => $item['price'] * $item['quantity']);

        return view('frontend.cart.index', [
            'items' => $items,
            'total' => number_format($total, 2),
        ]);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($data['product_id']);

        abort_unless($product->status, 404);

        $quantity = $data['quantity'] ?? 1;
        $cart = session('cart', ['items' => []]);
        $items = $cart['items'];

        if (isset($items[$product->id])) {
            $items[$product->id]['quantity'] += $quantity;
        } else {
            $items[$product->id] = [
                'id' => $product->id,
                'title' => $product->title,
                'slug' => $product->slug,
                'price' => $product->price ?? 0,
                'quantity' => $quantity,
                'image' => $product->image,
            ];
        }

        session(['cart' => ['items' => $items]]);

        if ($request->boolean('buy_now')) {
            return redirect()->route('checkout');
        }

        return back()->with('success', 'Product added to cart.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = session('cart', ['items' => []]);
        $items = $cart['items'];

        if (isset($items[$data['product_id']])) {
            $items[$data['product_id']]['quantity'] = $data['quantity'];
            session(['cart' => ['items' => $items]]);
            return back()->with('success', 'Cart updated successfully.');
        }

        return back()->with('error', 'Cart item not found.');
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
        ]);

        $cart = session('cart', ['items' => []]);
        $items = $cart['items'];

        if (isset($items[$data['product_id']])) {
            unset($items[$data['product_id']]);
            session(['cart' => ['items' => $items]]);
            return back()->with('success', 'Item removed from cart.');
        }

        return back()->with('error', 'Cart item not found.');
    }

    public function checkout()
    {
        $cart = session('cart', ['items' => []]);
        $items = collect($cart['items'])->values();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = $items->sum(fn($item) => $item['price'] * $item['quantity']);

        return view('frontend.cart.checkout', [
            'items' => $items,
            'total' => number_format($total, 2),
            'user' => Auth::user(),
        ]);
    }

    public function placeOrder(Request $request)
    {
        $cart = session('cart', ['items' => []]);
        $items = collect($cart['items'])->values();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $data = $request->validate([
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $total = $items->sum(fn($item) => $item['price'] * $item['quantity']);

        $order = Order::create([
            'user_id' => Auth::id(),
            'status' => Order::STATUS_PENDING,
            'total_amount' => $total,
            'note' => $data['note'] ?? null,
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['title'],
                'product_price' => $item['price'],
                'quantity' => $item['quantity'],
                'total_price' => $item['price'] * $item['quantity'],
            ]);
        }

        session()->forget('cart');

        return redirect()->route('home')->with('success', 'Your order has been placed and is pending review.');
    }

    public function reorder(Request $request)
    {
        $data = $request->validate([
            'order_id' => ['required', 'exists:orders,id'],
        ]);

        $order = Order::with('items')->findOrFail($data['order_id']);
        abort_unless($request->user()->id === $order->user_id, 403);

        $cart = session('cart', ['items' => []]);
        $items = $cart['items'];

        foreach ($order->items as $item) {
            $product = $item->product;
            $productId = $item->product_id;
            $quantity = $item->quantity;
            $slug = $product?->slug ?? '';
            $image = $product?->image ?? '';
            $title = $item->product_name;
            $price = $item->product_price;

            if (isset($items[$productId])) {
                $items[$productId]['quantity'] += $quantity;
            } else {
                $items[$productId] = [
                    'id' => $productId,
                    'title' => $title,
                    'slug' => $slug,
                    'price' => $price,
                    'quantity' => $quantity,
                    'image' => $image,
                ];
            }
        }

        session(['cart' => ['items' => $items]]);

        return redirect()->route('cart.index')->with('success', 'Order added to your cart for reorder.');
    }
}
