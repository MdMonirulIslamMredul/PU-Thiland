<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\VipRule;
use App\Services\OrderPaymentService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->orderQuery($request)
            ->with(['user', 'warehousePickingOrder'])
            ->withCount(['payments as pending_payments_count' => function ($q) {
                $q->where('status', 'pending');
            }]);

        $orders = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => Order::statuses(),
            'paymentStatuses' => Order::paymentStatuses(),
            'status' => $request->input('status', ''),
            'payment_status' => $request->input('payment_status', ''),
            'start_date' => $request->input('start_date', ''),
            'end_date' => $request->input('end_date', ''),
            'user_search' => $request->input('user_search', ''),
        ]);
    }

    public function exportPdf(Request $request)
    {
        $orders = $this->orderQuery($request)
            ->with(['user'])
            ->withCount(['payments as pending_payments_count' => function ($q) {
                $q->where('status', 'pending');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('admin.orders.export-pdf', compact('orders'));

        return $pdf->download('orders.pdf');
    }

    public function report(Request $request)
    {
        $orders = $this->orderQuery($request)
            ->with(['user'])
            ->withCount(['payments as pending_payments_count' => function ($q) {
                $q->where('status', 'pending');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.orders.report', [
            'orders' => $orders,
            'statuses' => Order::statuses(),
            'paymentStatuses' => Order::paymentStatuses(),
            'status' => $request->input('status', ''),
            'payment_status' => $request->input('payment_status', ''),
            'start_date' => $request->input('start_date', ''),
            'end_date' => $request->input('end_date', ''),
            'user_search' => $request->input('user_search', ''),
        ]);
    }

    public function show(Order $order)
    {
        $vipDiscountRate = null;

        if ($order->vip_level) {
            $vipDiscountRate = VipRule::active()
                ->where('level_name', $order->vip_level)
                ->value('discount_per_kg');
        }

        return view('admin.orders.show', [
            'order' => $order->load('user', 'items', 'warehousePickingOrder', 'payments.paymentGateway'),
            'statuses' => Order::statuses(),
            'vipDiscountRate' => $vipDiscountRate,
        ]);
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'payment_status' => ['required', 'in:' . implode(',', array_keys(Order::paymentStatuses()))],
        ]);

        $paymentStatus = $data['payment_status'];
        $order->payment_status = $paymentStatus;

        if ($paymentStatus === Order::PAYMENT_STATUS_PAID) {
            $order->paid_amount = $order->total_amount;
            $order->due_amount = 0;
        } elseif ($paymentStatus === Order::PAYMENT_STATUS_UNPAID) {
            $order->paid_amount = 0;
            $order->due_amount = $order->total_amount;
        }

        $order->save();

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order payment status updated successfully.');
    }

    public function updatePaymentRecord(Request $request, Order $order, OrderPayment $payment, OrderPaymentService $orderPaymentService)
    {
        abort_unless($payment->order_id === $order->id, 404);

        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', [OrderPayment::STATUS_CONFIRMED, OrderPayment::STATUS_FAILED])],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $wasConfirmed = $payment->status === OrderPayment::STATUS_CONFIRMED;
        $payment->status = $data['status'];
        $payment->note = $data['note'] ?? $payment->note;
        $payment->save();

        if ($payment->status === OrderPayment::STATUS_CONFIRMED || $wasConfirmed) {
            $orderPaymentService->refreshOrderPaymentSummary($order);
        }

        return redirect()->route('admin.orders.show', $order)->with('success', 'Payment record status updated successfully.');
    }

    private function orderQuery(Request $request)
    {
        $query = Order::query();

        if ($status = $request->input('status')) {
            if (array_key_exists($status, Order::statuses())) {
                $query->where('status', $status);
            }
        }

        if ($paymentStatus = $request->input('payment_status')) {
            if (array_key_exists($paymentStatus, Order::paymentStatuses())) {
                $query->where('payment_status', $paymentStatus);
            }
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        if ($userSearch = trim($request->input('user_search', ''))) {
            $query->whereHas('user', function ($query) use ($userSearch) {
                $query->where('email', 'like', "%{$userSearch}%")
                    ->orWhere('phone', 'like', "%{$userSearch}%");
            });
        }

        return $query;
    }

    public function edit(Order $order)
    {
        return view('admin.orders.edit', [
            'order' => $order,
            'statuses' => Order::statuses(),
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(Order::statuses()))],
        ]);

        if ($order->payment_status === Order::PAYMENT_STATUS_PARTIAL && $data['status'] === Order::STATUS_SUCCESSFUL) {
            return back()
                ->withErrors(['status' => 'Orders with partial payment cannot be marked as successful until the due amount is fully paid.'])
                ->withInput();
        }

        $order->update($data);

        return redirect()->route('admin.orders.index')->with('success', 'Order status updated successfully.');
    }
}
