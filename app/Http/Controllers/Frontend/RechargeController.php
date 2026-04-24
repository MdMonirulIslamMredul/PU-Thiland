<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\PaymentGatewayService;
use App\Services\RechargeOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RechargeController extends Controller
{
    public function __construct(
        protected PaymentGatewayService $paymentGatewayService,
        protected RechargeOrderService $rechargeOrderService
    ) {}

    public function index()
    {
        $user = Auth::user();

        return view('user.recharge-orders.index', [
            'user' => $user,
            'gateways' => $this->paymentGatewayService->getActive(),
            'orders' => $this->rechargeOrderService->getForUser($user),
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'payment_gateway_id' => ['required', 'exists:payment_gateways,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_method' => ['required', 'string', 'max:255'],
            'payment_proof' => ['required', 'image', 'max:5120'],
        ]);

        $file = $request->file('payment_proof');
        $data['payment_proof'] = $file ? $file->store('recharge_proofs', 'public') : null;
        $data['user_id'] = $user->id;
        $data['status'] = 'pending';

        $this->rechargeOrderService->createRequest($data);

        return redirect()->route('dashboard', ['tab' => 'recharge'])
            ->with('success', 'Recharge request submitted successfully. Please wait for admin validation.');
    }
}
