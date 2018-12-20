<?php

namespace Tests;

use App\Helpers\ApiClient\ApiClientService;

class DummyApiClientService extends ApiClientService
{
    public function retrieveCurrencyRates() : string
    {

        return '{
            "success" : true,
            "timestamp" : 1545294246,
            "base" : "EUR" , 
            "date" : "2018-12-20" ,
            "rates" : 
                {
                    "USD" : 1.142681,
                    "GBP" : 0.902746,
                    "EUR" : 1,
                    "JPY" : 127.859701
                 }
        }';
    }

}