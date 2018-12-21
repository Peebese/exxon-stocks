<?php


namespace App\Helpers\ApiClient;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use\Illuminate\Support\Facades\Cache;

class ApiClientService
{
    const CURRENCY_RATES = 'fixer_currencyself::self::CURRENCY_RATES';
    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $currencies;


    public function __construct()
    {
        $this->apiUrl       = env('FIXER_CONVERSION_URL');
        $this->apiKey       = env('FIXER_CONVERSION_KEY');
        $this->currencies   = env('CURRENCIES');
    }

    /**
     * Calls api, gets currency rates
     *
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCurrencyRates() : string
    {
        $request = new GuzzleRequest(
            'GET',
            $this->apiUrl.'?access_key='. $this->apiKey.'&symbols='.$this->currencies,
            [
                'Content-Type' => 'application/json'
            ]
        );

       $response =  (new Client())->send($request);
       $response->getBody()->rewind();

       $responseContent = $response->getBody()->getContents();
       $this->cacheResponse($responseContent);

       return $responseContent;
    }

    /**
     * Stores currency rates in cache
     *
     * @param string $response
     */
    private function cacheResponse(string $response) : void
    {
        Cache::put(self::CURRENCY_RATES, $response, env('CACHE_LIFE_MINS'));
    }

    /**
     * Retrieves rates from check if available
     *
     * @return string
     */
    public function retrieveCurrencyRates() : string
    {
        if (!Cache::get(self::CURRENCY_RATES)) {
            return $this->getCurrencyRates();
        }

        return Cache::get(self::CURRENCY_RATES);
    }
}