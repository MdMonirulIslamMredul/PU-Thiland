<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->orderQuery($request)->with('user');

        $orders = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => Order::statuses(),
            'status' => $request->input('status', ''),
            'start_date' => $request->input('start_date', ''),
            'end_date' => $request->input('end_date', ''),
        ]);
    }

    public function exportPdf(Request $request)
    {
        $orders = $this->orderQuery($request)->with('user')->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('admin.orders.export-pdf', compact('orders'));

        return $pdf->download('orders.pdf');
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', [
            'order' => $order->load('user', 'items'),
            'statuses' => Order::statuses(),
        ]);
    }

    private function orderQuery(Request $request)
    {
        $query = Order::query();

        if ($status = $request->input('status')) {
            if (array_key_exists($status, Order::statuses())) {
                $query->where('status', $status);
            }
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
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

        $order->update($data);

        return redirect()->route('admin.orders.index')->with('success', 'Order status updated successfully.');
    }
}
