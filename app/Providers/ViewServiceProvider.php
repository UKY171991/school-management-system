<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\GeneralSetting;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share general settings with all views
        View::composer('*', function ($view) {
            $general_settings = GeneralSetting::first();
            $view->with('general_settings', $general_settings);
        });
    }
}
