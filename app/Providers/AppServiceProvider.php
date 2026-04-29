<?php

namespace App\Providers;

use App\Http\Middleware\SetLocale;
use App\Models\Order;
use App\Models\RechargeOrder;
use App\Models\Setting;
use App\Models\WarehousePickingOrder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::share('settings', Setting::first());
        View::share('availableLocales', [
            'en' => 'English',
            'bn' => 'Bangla',
            'zh' => 'Chinese',
        ]);

        View::composer('*', function ($view) {
            $view->with('currentLocale', app()->getLocale());
        });

        View::composer('admin.partials.sidebar', function ($view) {
            $pendingOrdersCount = Order::where('status', Order::STATUS_PENDING)->count();

            $pendingRechargeOrdersCount = RechargeOrder::where('status', RechargeOrder::STATUS_PENDING)->count();

            $pendingPickingOrdersCount = WarehousePickingOrder::whereIn('status', [
                WarehousePickingOrder::STATUS_PENDING,
                WarehousePickingOrder::STATUS_PICKING,
            ])->count();

            $confirmedOrdersWithoutPickingCount = Order::where('status', Order::STATUS_CONFIRMED)
                ->whereDoesntHave('warehousePickingOrder')
                ->count();

            $pendingWarehouseCount = $pendingPickingOrdersCount + $confirmedOrdersWithoutPickingCount;

            $view->with([
                'sidebarPendingOrdersCount' => $pendingOrdersCount,
                'sidebarPendingRechargeOrdersCount' => $pendingRechargeOrdersCount,
                'sidebarPendingWarehouseCount' => $pendingWarehouseCount,
            ]);
        });
    }
}
