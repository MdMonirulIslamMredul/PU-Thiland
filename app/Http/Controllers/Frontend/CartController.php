<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\PaymentGatewayService;
use App\Services\VipCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', ['items' => []]);
        $items = collect($cart['items'])->values();
        $total = $items->sum(fn($item) => $item['price'] * $item['quantity']);
        $totalWeight = $items->sum(fn($item) => ($item['weight'] ?? 0) * $item['quantity']);

        return view('frontend.cart.index', [
            'items' => $items,
            'total' => number_format($total, 2),
            'totalWeight' => number_format($totalWeight, 2),
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
                'weight' => $product->weight ?? 0,
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

    public function checkout(VipCalculationService $vipCalculationService, PaymentGatewayService $paymentGatewayService)
    {
        $cart = session('cart', ['items' => []]);
        $items = collect($cart['items'])->values();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $user = Auth::user();
        $total = $items->sum(fn($item) => $item['price'] * $item['quantity']);
        $totalWeight = $items->sum(fn($item) => ($item['weight'] ?? 0) * $item['quantity']);
        $addresses = $user->addresses()->orderByDesc('created_at')->take(3)->get();
        $vipRule = $vipCalculationService->getEffectiveVipRuleForUser($user);
        $vipDiscount = $vipCalculationService->calculateOrderDiscount($user, $items->toArray());
        $paymentGateways = $paymentGatewayService->getActive();

        return view('frontend.cart.checkout', [
            'items' => $items,
            'total' => number_format($total, 2),
            'totalWeight' => number_format($totalWeight, 2),
            'vipDiscount' => number_format($vipDiscount, 2),
            'finalTotal' => number_format(max(0, $total - $vipDiscount), 2),
            'user' => $user,
            'addresses' => $addresses,
            'vipRule' => $vipRule,
            'paymentGateways' => $paymentGateways,
        ]);
    }

    public function placeOrder(Request $request, VipCalculationService $vipCalculationService, PaymentGatewayService $paymentGatewayService)
    {
        $cart = session('cart', ['items' => []]);
        $items = collect($cart['items'])->values();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $user = Auth::user();

        $paymentMethods = $paymentGatewayService->getActive()
            ->pluck('mfs_name')
            ->map(fn($name) => strtolower($name))
            ->toArray();

        $data = $request->validate([
            'address_id' => ['nullable', 'exists:user_addresses,id'],
            'delivery_recipient_name' => ['required_without:address_id', 'string', 'max:255'],
            'delivery_phone' => ['required_without:address_id', 'string', 'max:50'],
            'delivery_address' => ['required_without:address_id', 'string'],
            'save_address' => ['nullable', 'boolean'],
            'payment_method' => ['required', 'string', Rule::in(array_merge(['wallet'], $paymentMethods))],
            'payment_gateway_id' => ['nullable', 'exists:payment_gateways,id'],
            'payment_proof' => ['nullable', 'image', 'max:2048'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($data['payment_method'] !== 'wallet') {
            if (empty($data['payment_gateway_id'])) {
                return back()->withErrors(['payment_gateway_id' => 'Please select a payment gateway.'])->withInput();
            }

            if (! $request->hasFile('payment_proof')) {
                return back()->withErrors(['payment_proof' => 'Please upload a payment receipt image.'])->withInput();
            }
        }

        $deliveryRecipient = null;
        $deliveryPhone = null;
        $deliveryAddress = null;
        $userAddressId = null;

        if (!empty($data['address_id'])) {
            $address = $user->addresses()->find($data['address_id']);
            if (! $address) {
                return back()->with('error', 'Selected address was not found.');
            }

            $deliveryRecipient = $address->recipient_name;
            $deliveryPhone = $address->phone;
            $deliveryAddress = $address->address;
            $userAddressId = $address->id;
        } else {
            $deliveryRecipient = $data['delivery_recipient_name'];
            $deliveryPhone = $data['delivery_phone'];
            $deliveryAddress = $data['delivery_address'];

            if ($request->boolean('save_address') && $user->addresses()->count() < 3) {
                $saved = $user->addresses()->create([
                    'recipient_name' => $deliveryRecipient,
                    'phone' => $deliveryPhone,
                    'address' => $deliveryAddress,
                ]);
                $userAddressId = $saved->id;
            }
        }

        $total = $items->sum(fn($item) => $item['price'] * $item['quantity']);
        $totalWeight = $items->sum(fn($item) => ($item['weight'] ?? 0) * $item['quantity']);
        $vipDiscount = $vipCalculationService->calculateOrderDiscount($user, $items->toArray());
        $totalAfterDiscount = max(0, $total - $vipDiscount);
        $vipRule = $vipCalculationService->getEffectiveVipRuleForUser($user);

        $rechargeUsedAmount = 0;
        if ($data['payment_method'] === 'wallet') {
            $rechargeUsedAmount = min($user->recharge_amount, $totalAfterDiscount);
            if ($rechargeUsedAmount > 0) {
                $user->decrement('recharge_amount', $rechargeUsedAmount);
            }
        }

        $finalTotal = max(0, $totalAfterDiscount - $rechargeUsedAmount);

        $paymentReceiptPath = null;
        if ($request->hasFile('payment_proof')) {
            $paymentReceiptPath = $request->file('payment_proof')->store('payment_receipts', 'public');
        }

        $order = Order::create([
            'user_id' => $user->id,
            'status' => Order::STATUS_PENDING,
            'vip_level' => $vipRule?->level_name,
            'total_amount' => $totalAfterDiscount,
            'total_weight' => $totalWeight,
            'vip_discount_amount' => $vipDiscount,
            'payment_method' => $data['payment_method'],
            'payment_receipt' => $paymentReceiptPath,
            'recharge_used_amount' => $rechargeUsedAmount,
            'note' => $data['note'] ?? null,
            'user_address_id' => $userAddressId,
            'delivery_recipient_name' => $deliveryRecipient,
            'delivery_phone' => $deliveryPhone,
            'delivery_address' => $deliveryAddress,
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

        return redirect()->route('dashboard', ['tab' => 'orders'])->with('success', 'Your order has been placed and is pending review.');
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
            $weight = $product?->weight ?? 0;

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
                    'weight' => $weight,
                ];
            }
        }

        session(['cart' => ['items' => $items]]);

        return redirect()->route('cart.index')->with('success', 'Order added to your cart for reorder.');
    }
}
