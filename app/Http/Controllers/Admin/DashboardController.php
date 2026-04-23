<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\Gallery;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\TeamMember;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();
        $yearStart = $now->copy()->startOfYear();
        $yearEnd = $now->copy()->endOfYear();

        $orderBaseQuery = Order::whereIn('status', [
            Order::STATUS_PENDING,
            Order::STATUS_CONFIRMED,
            Order::STATUS_SUCCESSFUL,
        ]);

        $currentMonthSales = (clone $orderBaseQuery)->whereBetween('created_at', [$monthStart, $monthEnd])->sum('total_amount');
        $currentYearSales = (clone $orderBaseQuery)->whereBetween('created_at', [$yearStart, $yearEnd])->sum('total_amount');

        $currentMonthKg = (clone $orderBaseQuery)->whereBetween('created_at', [$monthStart, $monthEnd])->sum('total_weight');
        $currentYearKg = (clone $orderBaseQuery)->whereBetween('created_at', [$yearStart, $yearEnd])->sum('total_weight');

        $monthlyAveragePrice = $currentMonthKg > 0 ? $currentMonthSales / $currentMonthKg : 0;
        $yearlyAveragePrice = $currentYearKg > 0 ? $currentYearSales / $currentYearKg : 0;

        $trend = collect(range(5, 0))->map(function ($subMonths) use ($orderBaseQuery, $now) {
            $month = $now->copy()->subMonths($subMonths);
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            return [
                'label' => $month->format('M Y'),
                'sales' => (clone $orderBaseQuery)->whereBetween('created_at', [$start, $end])->sum('total_amount'),
            ];
        });

        return view('admin.dashboard', [
            'stats' => [
                'products' => Product::count(),
                'services' => Service::count(),
                'blogs' => Blog::count(),
                'team_members' => TeamMember::count(),
                'galleries' => Gallery::count(),
                'contacts' => Contact::count(),
            ],
            'recentContacts' => Contact::latest()->take(5)->get(),
            'currentMonthSales' => $currentMonthSales,
            'currentMonthKg' => $currentMonthKg,
            'currentYearSales' => $currentYearSales,
            'currentYearKg' => $currentYearKg,
            'currentMonthAvgPrice' => $monthlyAveragePrice,
            'currentYearAvgPrice' => $yearlyAveragePrice,
            'salesTrend' => $trend,
            'outstandingOrders' => Order::whereIn('status', [Order::STATUS_PENDING, Order::STATUS_CONFIRMED])->count(),
        ]);
    }
}
