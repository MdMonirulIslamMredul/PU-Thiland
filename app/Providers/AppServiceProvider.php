<?php

namespace App\Providers;

use App\Http\Middleware\SetLocale;
use App\Models\Setting;
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
    }
}
