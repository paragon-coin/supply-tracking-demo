<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('eth', function () { return new \App\Components\Ethereum(); });
        $this->app->singleton('spc', function () { return new \App\Components\SupplyContract(); });
        $this->app->singleton('spcv2', function () { return new \App\Components\ContractV2(); });
        $this->app->singleton('spcv3', function () { return new \App\Components\ContractV3(); });
    }
}
