<?php

namespace App\Http\Controllers;


use App\StockPrices;
use App\Helpers\ApiClient\ApiClientService;

class ClosingPricesController extends Controller
{
    /**
     * @const bool
     */
    const RESPONSE_SUCCESS  = true;

    /**
     * @const bool
     */
    const RESPONSE_FAIL     = false;

    /**
     * @const int
     */
    const STATUS_OK         = 200;

    /**
     * @const int
     */
    const STATUS_NOT_FOUND  = 404;

    /**
     * @var ApiClientService
     */
    private $apiClient;

    /**
     * ClosingPricesController constructor.
     * @param ApiClientService $apiClient
     */
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
                    'success' => self::RESPONSE_FAIL,
                    'status' => self::STATUS_NOT_FOUND,
                    'message' => 'No results for date range: '. $dateFrom .' - ' . $dateTo
                ]
            );
        }

        $converter = \app()->makeWith('converter', ['currency' => $currency]);
        $convertedStocks = $converter->convertStocks($resultsArr->toArray());

        return response()->json(
            [
                'success' => self::RESPONSE_SUCCESS,
                'status' => self::STATUS_OK,
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
                    'success' => self::RESPONSE_FAIL,
                    'status' => self::STATUS_NOT_FOUND,
                    'message' => 'No results for date range: '. $dateFrom .' - ' . $dateTo
                ]
            );
        }

        $converter = \app()->makeWith('converter', ['currency' => $currency]);

        $stockReport = StockPrices::formatCollectionReport($resultsArr, $converter);

            return response()->json(
                [
                    'success' => self::RESPONSE_SUCCESS,
                    'status' => self::STATUS_OK,
                    'stocks' => [
                        'currency' => $converter->currency,
                        'report' => $stockReport
                    ]
                ]);
    }
}