<?php

namespace App\Http\Middleware;

use App\Models\Order;
use Closure;
use Illuminate\Http\Request;

class BlockCheckoutIfDueExists
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->orders()->whereIn('payment_status', [Order::PAYMENT_STATUS_UNPAID, Order::PAYMENT_STATUS_PARTIAL])->exists()) {
            return redirect()->route('dashboard')->with('error', 'Your account has unpaid or partially paid orders. Please settle existing dues before placing a new order.');
        }

        return $next($request);
    }
}
