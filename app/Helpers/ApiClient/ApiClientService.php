<?php


namespace App\Helpers\ApiClient;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use\Illuminate\Support\Facades\Cache;

class ApiClientService
{
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

    private function cacheResponse(string $response) : void
    {
        Cache::put('fixer_currency',$response,60);
    }

    public function retrieveCurrencyRates() : string
    {
        if (!Cache::get('fixer_currency')) {
            return $this->getCurrencyRates();
        }

        return Cache::get('fixer_currency');
    }
}