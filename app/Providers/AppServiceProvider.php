<?php

namespace App\Providers;

use App\Helpers\ApiClient\ApiClientService;
use App\Helpers\Stocks\CurrencyStockConverter;
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
        $this->registerCurrencyStockConverter();

    }

    public function registerCurrencyStockConverter()
    {
        $this->app->bind('converter', function($app , $params ) {
            return  new CurrencyStockConverter(New ApiClientService, $params['currency']);
        });
    }
}
