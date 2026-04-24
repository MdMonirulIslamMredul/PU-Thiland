<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PaymentGatewayService;
use App\Services\RechargeOrderService;
use App\Services\VipCalculationService;
use App\Services\VipRuleService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function __construct(
        protected PaymentGatewayService $paymentGatewayService,
        protected RechargeOrderService $rechargeOrderService,
        protected VipCalculationService $vipCalculationService,
        protected VipRuleService $vipRuleService
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();

        if ($user?->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        $orders = Order::where('user_id', $user->id)
            ->withCount('items')
            ->latest()
            ->get();

        $addresses = $user->addresses()->orderByDesc('created_at')->get();
        $rechargeOrders = $this->rechargeOrderService->getForUser($user);
        $paymentGateways = $this->paymentGatewayService->getActive();

        $currentMonth = Carbon::now()->startOfMonth();
        $currentMonthOrders = Order::where('user_id', $user->id)
            ->whereIn('status', [Order::STATUS_PENDING, Order::STATUS_CONFIRMED, Order::STATUS_SUCCESSFUL])
            ->where('created_at', '>=', $currentMonth)
            ->withCount('items')
            ->get();

        $dashboardStats = [
            'currentMonthPurchaseAmount' => $currentMonthOrders->sum('total_amount'),
            'currentMonthPurchaseItems' => $currentMonthOrders->sum('items_count'),
            'currentMonthPurchaseWeight' => $currentMonthOrders->sum('total_weight'),
            'totalPurchaseAmount' => $orders->sum('total_amount'),
            'totalPurchaseItems' => $orders->sum('items_count'),
            'totalPurchaseWeight' => $orders->sum('total_weight'),
            'availableRecharge' => $user->recharge_amount ?? 0,
            'currentMonthRecharge' => $user->rechargeOrders()->where('status', 'approved')->where('created_at', '>=', $currentMonth)->sum('amount'),
            'totalRecharge' => $user->rechargeOrders()->where('status', 'approved')->sum('amount'),
            'vipLevel' => $user->vip_level ? ucfirst($user->vip_level) : 'None',
            'vipDiscount' => $this->vipRuleService->getActiveRules()->first(fn($rule) => $rule->level_name === $user->vip_level)?->discount_per_kg ?? 0,
        ];

        [$nextVipLevel, $nextVipProgress] = $this->getNextVipProgress($user);
        $dashboardStats['nextVipLevel'] = $nextVipLevel;
        $dashboardStats['nextVipProgress'] = $nextVipProgress;

        return view('user.dashboard', compact('user', 'orders', 'addresses', 'rechargeOrders', 'paymentGateways', 'dashboardStats'));
    }

    public function showOrder(Order $order)
    {
        $user = request()->user();
        abort_unless($user?->id === $order->user_id, 404);

        $order->load('items');

        return view('user.orders.show', compact('order'));
    }

    private function getNextVipProgress($user): array
    {
        $monthlySalesKg = $this->vipCalculationService->getUserMonthlySalesKg($user);
        $monthlyRecharge = $this->vipCalculationService->getUserMonthlyRecharge($user);
        $activeRules = $this->vipRuleService->getActiveRules();
        $currentRule = $activeRules->first(fn($rule) => $rule->level_name === $user->vip_level);

        $nextRule = null;
        if ($currentRule) {
            $nextRule = $activeRules->first(fn($rule) => $rule->priority > $currentRule->priority);
        }

        if (! $nextRule) {
            $nextRule = $activeRules->first(fn($rule) => ! $this->vipRuleService->findMatchingRule($monthlySalesKg, $monthlyRecharge)?->is($rule));
        }

        if (! $nextRule) {
            return [null, 1.0];
        }

        $progressSales = $nextRule->min_sales_kg > 0 ? min(1.0, $monthlySalesKg / $nextRule->min_sales_kg) : 0.0;
        $progressRecharge = $nextRule->min_recharge_amount > 0 ? min(1.0, $monthlyRecharge / $nextRule->min_recharge_amount) : 0.0;
        $progress = max($progressSales, $progressRecharge);

        return [ucfirst($nextRule->level_name), $progress];
    }
}
