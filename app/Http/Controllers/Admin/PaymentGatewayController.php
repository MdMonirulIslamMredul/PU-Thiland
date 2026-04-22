<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function __construct(protected PaymentGatewayService $paymentGatewayService) {}

    public function index()
    {
        return view('admin.payment-gateways.index', [
            'gateways' => $this->paymentGatewayService->getAll(),
        ]);
    }

    public function create()
    {
        return view('admin.payment-gateways.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateGateway($request);

        $this->paymentGatewayService->create($data);

        return redirect()->route('admin.payment-gateways.index')->with('success', 'Payment gateway added successfully.');
    }

    public function edit(PaymentGateway $paymentGateway)
    {
        return view('admin.payment-gateways.edit', compact('paymentGateway'));
    }

    public function update(Request $request, PaymentGateway $paymentGateway)
    {
        $data = $this->validateGateway($request);

        $this->paymentGatewayService->update($paymentGateway, $data);

        return redirect()->route('admin.payment-gateways.index')->with('success', 'Payment gateway updated successfully.');
    }

    public function destroy(PaymentGateway $paymentGateway)
    {
        $this->paymentGatewayService->delete($paymentGateway);

        return back()->with('success', 'Payment gateway removed successfully.');
    }

    protected function validateGateway(Request $request): array
    {
        return $request->validate([
            'mfs_name' => ['required', 'string', 'max:255'],
            'account_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:255'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string'],
        ]) + ['is_active' => $request->boolean('is_active', true)];
    }
}
