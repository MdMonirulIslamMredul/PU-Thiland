<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\OrderPaymentService;
use App\Services\PaymentGatewayService;
use App\Services\VipCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $dueOrdersCount = $user->orders()
            ->whereIn('payment_status', [Order::PAYMENT_STATUS_UNPAID, Order::PAYMENT_STATUS_PARTIAL])
            ->count();
        $dueAmountTotal = $user->orders()
            ->whereIn('payment_status', [Order::PAYMENT_STATUS_UNPAID, Order::PAYMENT_STATUS_PARTIAL])
            ->sum('due_amount');

        return view('frontend.cart.checkout', [
            'items' => $items,
            'total' => $total,
            'totalWeight' => $totalWeight,
            'vipDiscount' => $vipDiscount,
            'finalTotal' => max(0, $total - $vipDiscount),
            'user' => $user,
            'addresses' => $addresses,
            'vipRule' => $vipRule,
            'paymentGateways' => $paymentGateways,
            'hasDueOrders' => $dueOrdersCount > 0,
            'dueOrdersCount' => $dueOrdersCount,
            'dueAmountTotal' => number_format($dueAmountTotal, 2),
        ]);
    }

    public function placeOrder(Request $request, VipCalculationService $vipCalculationService, PaymentGatewayService $paymentGatewayService, OrderPaymentService $orderPaymentService)
    {
        $cart = session('cart', ['items' => []]);
        $items = collect($cart['items'])->values();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $user = Auth::user();

        if ($user->orders()->whereIn('payment_status', [Order::PAYMENT_STATUS_UNPAID, Order::PAYMENT_STATUS_PARTIAL])->exists()) {
            return redirect()->route('dashboard')->with('error', 'Please settle outstanding orders before placing a new order.');
        }

        $paymentMethods = $paymentGatewayService->getActive()
            ->pluck('mfs_name')
            ->map(fn($name) => strtolower($name))
            ->toArray();

        $data = $request->validate([
            'address_id' => ['nullable', 'exists:user_addresses,id'],
            'delivery_recipient_name' => ['nullable', 'required_without:address_id', 'string', 'max:255'],
            'delivery_phone' => ['nullable', 'required_without:address_id', 'string', 'max:50'],
            'delivery_address' => ['nullable', 'required_without:address_id', 'string'],
            'save_address' => ['nullable', 'boolean'],
            'payment_method' => ['required', 'string', Rule::in(array_merge(['wallet', 'partial'], $paymentMethods))],
            'partial_payment_method' => ['nullable', 'string', Rule::in(array_merge(['wallet'], $paymentMethods))],
            'payment_amount' => ['required_if:payment_method,partial', 'numeric', 'min:0.01'],
            'payment_gateway_id' => ['nullable', 'exists:payment_gateways,id'],
            'payment_proof' => ['nullable', 'image', 'max:2048'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($data['payment_method'] === 'partial') {
            if (empty($data['partial_payment_method'])) {
                return back()->withErrors(['partial_payment_method' => 'Please select a partial payment method.'])->withInput();
            }

            if ($data['partial_payment_method'] !== 'wallet') {
                if (empty($data['payment_gateway_id'])) {
                    return back()->withErrors(['payment_gateway_id' => 'Please select a payment gateway for partial payment.'])->withInput();
                }

                if (! $request->hasFile('payment_proof')) {
                    return back()->withErrors(['payment_proof' => 'Please upload a payment receipt image for partial payment.'])->withInput();
                }
            }
        } elseif ($data['payment_method'] !== 'wallet') {
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

        $paymentAmount = $totalAfterDiscount;
        $paymentSourceMethod = $data['payment_method'];

        if ($data['payment_method'] === 'partial') {
            $paymentAmount = round($data['payment_amount'], 2);
            if ($paymentAmount > $totalAfterDiscount) {
                return back()->withErrors(['payment_amount' => 'Payment amount cannot exceed the order total.'])->withInput();
            }
            $paymentSourceMethod = $data['partial_payment_method'];
        }

        $rechargeUsedAmount = 0;
        if ($paymentSourceMethod === 'wallet') {
            if ($paymentAmount > $user->recharge_amount) {
                return back()->withErrors(['payment_amount' => 'Your wallet balance is insufficient for this payment amount.'])->withInput();
            }

            $rechargeUsedAmount = $paymentAmount;
            if ($rechargeUsedAmount > 0) {
                $user->decrement('recharge_amount', $rechargeUsedAmount);
            }
        }

        $paidAmount = $paymentAmount;
        $dueAmount = max(0, $totalAfterDiscount - $paymentAmount);
        $paymentStatus = $dueAmount > 0 ? Order::PAYMENT_STATUS_PARTIAL : Order::PAYMENT_STATUS_PAID;

        $finalTotal = max(0, $totalAfterDiscount - $paidAmount);

        $paymentReceiptPath = null;
        if ($request->hasFile('payment_proof')) {
            $paymentReceiptPath = $request->file('payment_proof')->store('payment_receipts', 'public');
        }

        $order = Order::create([
            'user_id' => $user->id,
            'status' => Order::STATUS_PENDING,
            'vip_level' => $vipRule?->level_name,
            'total_amount' => $totalAfterDiscount,
            'paid_amount' => $paidAmount,
            'due_amount' => $dueAmount,
            'payment_status' => $paymentStatus,
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

        if ($paidAmount > 0) {
            $paymentGateway = null;
            if (! empty($data['payment_gateway_id'])) {
                $paymentGateway = $paymentGatewayService->find($data['payment_gateway_id']);
            }

            $orderPaymentService->createPayment(
                $order,
                $paidAmount,
                $paymentSourceMethod,
                $paymentGateway,
                $paymentReceiptPath,
                'Checkout payment',
                OrderPaymentService::STATUS_CONFIRMED
            );
        }

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
