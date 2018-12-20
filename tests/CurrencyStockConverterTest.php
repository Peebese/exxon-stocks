<?php

namespace Tests;

use App\Helpers\ApiClient\ApiClientService;
use App\Helpers\Stocks\CurrencyStockConverter;
use PHPUnit\Framework\TestCase;

class CurrencyStockConverterTest extends TestCase
{
    private $currencyConverter;
    private $apiClient;

    public function setUp()
    {
        $this->apiClient = new ApiClientService();
        $this->currencyConverter = new CurrencyStockConverter($this->apiClient,'GBP');
    }

    public function testConvertSingleStock()
    {
        $stock = 27.3;
        $convertedStock = $this->currencyConverter->convertSingleStock($stock);
        $this->assertNotEquals($stock, $convertedStock);
        $this->assertTrue(gettype($convertedStock) === 'double');
    }
}