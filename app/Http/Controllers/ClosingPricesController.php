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
            return response()->json(
                [
                    'success' => false,
                    'status' => 404,
                    'message' => 'No results for date range: '. $dateFrom .' - ' . $dateTo
                ]
            );
        }

        $converter = new CurrencyStockConverter($this->apiClient,$currency);
        $convertedStocks = $converter->convertStocks($resultsArr->toArray());

        return response()->json(
            [
                'success' => true,
                'status' => 200,
                'stocks' => [
                    'currency' => $converter->currency,
                    'closing_stock' => $convertedStocks
                ]
            ]);
    }

    /**
     * @param string $currency
     * @param string $dateFrom
     * @param string $dateTo
     * @return string
     */
    public function ShowClosingPricesReport(string $currency, string $dateFrom, string $dateTo)
    {
        $resultsArr = StockPrices::getCollection($dateFrom, $dateTo);

        if (empty($resultsArr->count())) {
            return response()->json(
                [
                    'success' => false,
                    'status' => 404,
                    'message' => 'No results for date range: '. $dateFrom .' - ' . $dateTo
                ]
            );
        }

        $converter = new CurrencyStockConverter($this->apiClient, $currency);
        $stockReport = StockPrices::formatCollectionReport($resultsArr, $converter);

            return response()->json(
                [
                    'success' => true,
                    'status' => 200,
                    'stocks' => [
                        'currency' => $converter->currency,
                        'report' => $stockReport
                    ]
                ]);
    }
}