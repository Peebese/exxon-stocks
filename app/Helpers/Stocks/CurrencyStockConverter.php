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

    /**
     * CurrencyStockConverter constructor.
     * @param ApiClientService $apiClient
     * @param string $currency
     */
    public function __construct(
        ApiClientService $apiClient,
        string $currency = self::DEFAULT_CURRENCY
    )
    {
        $this->currency  = self::validateCurrency($currency) ? strtoupper($currency) : self::DEFAULT_CURRENCY;
        $this->apiClient = $apiClient;
        $this->currencyRate = $this->retrieveCurrencyRate();
    }

    /**
     * Checks currency exists in allowed currencies list
     *
     * @param $requestCurrency
     * @return bool
     */
    private static function validateCurrency($requestCurrency) : bool
    {
        $allowedCurrencies = explode(',', env('CURRENCIES'));
        return in_array(strtoupper($requestCurrency), $allowedCurrencies);
    }

    /**
     * Convert stock prices in array
     *
     * @param array $stocks
     * @return array
     */
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

    /**
     * Performs conversion
     *
     * @param float $stockPrice
     * @return float
     */
    public function convertSingleStock(float $stockPrice) : float
    {
        $convertedPrice = ($stockPrice * $this->currencyRate);
        return self::roundNearest2Dec($convertedPrice);
    }

    /**
     * Returns currency rate
     *
     * @return float
     */
    private function retrieveCurrencyRate() : float
    {
        $currencies = json_decode($this->apiClient->retrieveCurrencyRates(),true);
        return $currencies['rates'][$this->currency];
    }

    /**
     * @param float $number
     * @return float
     */
    private static function roundNearest2Dec(float $number) : float
    {
        return round($number,2);
    }
}