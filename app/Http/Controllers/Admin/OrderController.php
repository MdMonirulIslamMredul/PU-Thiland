<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin.orders.index', [
            'orders' => Order::with('user')->latest()->paginate(15),
            'statuses' => Order::statuses(),
        ]);
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', [
            'order' => $order->load('user', 'items'),
            'statuses' => Order::statuses(),
        ]);
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
