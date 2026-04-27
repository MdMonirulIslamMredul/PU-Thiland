<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentGateway;
use App\Services\OrderPaymentService;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OrderPaymentController extends Controller
{
    public function store(Request $request, Order $order, OrderPaymentService $orderPaymentService, PaymentGatewayService $paymentGatewayService)
    {
        abort_unless(Auth::id() === $order->user_id, 403);

        $paymentMethods = $paymentGatewayService->getActive()
            ->pluck('mfs_name')
            ->map(fn($name) => strtolower($name))
            ->toArray();

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'lte:' . $order->due_amount],
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

        $paymentGateway = null;
        if (! empty($data['payment_gateway_id'])) {
            $paymentGateway = PaymentGateway::find($data['payment_gateway_id']);
        }

        $paymentReceiptPath = null;
        if ($request->hasFile('payment_proof')) {
            $paymentReceiptPath = $request->file('payment_proof')->store('payment_receipts', 'public');
        }

        $payment = $orderPaymentService->createPayment(
            $order,
            floatval($data['amount']),
            $data['payment_method'],
            $paymentGateway,
            $paymentReceiptPath,
            $data['note'] ?? null
        );

        return back()->with('success', 'Payment of ৳' . number_format($payment->amount, 2) . ' has been recorded and will be applied to your order.');
    }
}
