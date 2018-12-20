<?php


namespace App\Helpers\Stocks;


use App\Helpers\ApiClient\ApiClientService;


class CurrencyStockConverter implements CurrencyStockConverterInterface
{
    const DEFAULT_CURRENCY = 'USD';

    /**
     * @var string
     */
    public $currency;

    /**
     * @var float
     */
    private $currencyRate;

    /**
     * @var Object
     */
    private $apiClient;


    public function __construct(
        ApiClientService $apiClient,
        string $currency = self::DEFAULT_CURRENCY
    )
    {
        $this->currency  = self::validateCurrency($currency) ? strtoupper($currency) : self::DEFAULT_CURRENCY;
        $this->apiClient = $apiClient;
        $this->currencyRate = $this->retrieveCurrencyRate();
    }

    private static function validateCurrency($requestCurrency) : bool
    {
        $allowedCurrencies = explode(',', env('CURRENCIES'));
        return in_array(strtoupper($requestCurrency), $allowedCurrencies);
    }

    public function convertStocks(array $stocks): array
    {
        $this->currencyRate = $this->retrieveCurrencyRate();

        $returnArray = function ($stock) {
            return [
                'date' => $stock['date'],
                'closing_price' => $this->convertSingleStock($stock['closing_price'])
            ];
        };

        return array_map($returnArray, $stocks);
    }

    public function convertSingleStock(float $stockPrice): float
    {
        $convertedPrice = ($stockPrice * $this->currencyRate);
        return self::roundNearest2Dec($convertedPrice);
    }

    private function retrieveCurrencyRate() : float
    {
        $currencies = json_decode($this->apiClient->retrieveCurrencyRates(),true);
        return $currencies['rates'][$this->currency];
    }

    private static function roundNearest2Dec(float $number) : float
    {
        return round($number,2);
    }
}