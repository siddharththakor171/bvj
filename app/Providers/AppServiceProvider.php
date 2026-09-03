<?php

namespace App\Providers;

use App\Models\StoreSetting;
use App\Services\LiveMetalRateService;
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
        View::composer('layouts.customer', function ($view): void {
            $view->with([
                'rates' => app(LiveMetalRateService::class)->currentRates(),
                'storeSetting' => StoreSetting::firstOrFail(),
            ]);
        });
    }
}
