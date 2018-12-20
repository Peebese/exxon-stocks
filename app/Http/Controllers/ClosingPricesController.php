<?php

namespace App\Http\Controllers;

use App\Helpers\Stocks\CurrencyStockConverter;
use App\StockPrices;
use App\Helpers\ApiClient\ApiClientService;

class ClosingPricesController extends Controller
{
    /**
     * @var ApiClientService
     */
    private $apiClient;


    public function __construct(
        ApiClientService $apiClient
    )
    {
        $this->apiClient = $apiClient;
    }

    /**
     * @param string $currency
     * @param string $dateFrom
     * @param string $dateTo
     * @return \Illuminate\Http\JsonResponse
     */
    public function showClosingPrices(string $currency, string $dateFrom, string $dateTo)
    {
        $resultsArr = StockPrices::getCollection($dateFrom, $dateTo);

        if (empty($resultsArr->count())) {
            return response()->json('No results for date range: '. $dateFrom .' - ' . $dateTo);
        }

        $converter = new CurrencyStockConverter($this->apiClient,$currency);
        $convertedStocks = $converter->convertStocks($resultsArr->toArray());

        return response()->json($convertedStocks);
    }

    /**
     * @param string $currency
     * @param string $dateFrom
     * @param string $dateTo
     * @return string
     */
    public function ShowClosingPricesReport(string $currency, string $dateFrom, string $dateTo) : string
    {
        $resultsArr = StockPrices::getCollection($dateFrom, $dateTo);

        if (empty($resultsArr->count())) {
            return response()->json('No results for date range: '. $dateFrom .' - ' . $dateTo)->content();
        }

        $converter = new CurrencyStockConverter($this->apiClient, $currency);
        $stockReport = StockPrices::formatCollectionReport($resultsArr, $converter);

        return response()->json($stockReport)->content();
    }
}