<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\DB;

class OrderPaymentService
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_FAILED = 'failed';

    public function __construct(protected VipCalculationService $vipCalculationService) {}

    public function createPayment(Order $order, float $amount, string $paymentMethod, ?PaymentGateway $paymentGateway = null, ?string $paymentReceipt = null, ?string $note = null, string $status = self::STATUS_PENDING): OrderPayment
    {
        return DB::transaction(function () use ($order, $amount, $paymentMethod, $paymentGateway, $paymentReceipt, $note, $status) {
            $payment = $order->payments()->create([
                'user_id' => $order->user_id,
                'payment_method' => $paymentMethod,
                'payment_gateway_id' => $paymentGateway?->id,
                'amount' => $amount,
                'status' => $status,
                'payment_receipt' => $paymentReceipt,
                'note' => $note,
            ]);

            if ($status === self::STATUS_CONFIRMED) {
                $this->refreshOrderPaymentSummary($order);
            }

            return $payment;
        });
    }

    public function refreshOrderPaymentSummary(Order $order): Order
    {
        $confirmedPaid = $order->payments()
            ->where('status', self::STATUS_CONFIRMED)
            ->sum('amount');

        $order->paid_amount = $confirmedPaid;
        $order->due_amount = max(0, $order->total_amount - $confirmedPaid);

        if ($order->due_amount <= 0) {
            $order->payment_status = Order::PAYMENT_STATUS_PAID;
        } elseif ($confirmedPaid > 0) {
            $order->payment_status = Order::PAYMENT_STATUS_PARTIAL;
        } else {
            $order->payment_status = Order::PAYMENT_STATUS_UNPAID;
        }

        $order->save();

        $this->vipCalculationService->recalculateForUser(
            $order->user,
            'system',
            'VIP recalculated after order payment update'
        );

        return $order;
    }
}
