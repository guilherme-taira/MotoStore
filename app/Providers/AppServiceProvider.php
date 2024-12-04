<?php

namespace App\Providers;

use App\Models\IntegracaoBling;
use App\Models\ShippingUpdate;
use App\Observers\ShippingUpdateObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();
        ShippingUpdate::observe(ShippingUpdateObserver::class);
    }
}
