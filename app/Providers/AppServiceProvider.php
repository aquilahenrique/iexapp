<?php

namespace App\Providers;

use App\Integrations\IEXCloud;
use IEXApp\Quote\Contracts\QuoteRepository;
use IEXApp\Quote\Contracts\StockIntegration;
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
        $this->app->bind(StockIntegration::class, IEXCloud::class);
        $this->app->bind(QuoteRepository::class, \App\Repositories\QuoteRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
