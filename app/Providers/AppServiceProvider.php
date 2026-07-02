<?php

namespace App\Providers;

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
        //\App\Services\ScaleService::start();
        /*if (!app()->runningInConsole()) {
            \App\Services\ScaleService::start();
        }*/
    }
}
