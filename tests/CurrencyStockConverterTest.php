<?php

namespace Tests;

use App\Helpers\Stocks\CurrencyStockConverter;
use PHPUnit\Framework\TestCase;

class CurrencyStockConverterTest extends TestCase
{
    /**
     * @var object
     */
    private $currencyStockConverter;

    /**
     * @var object
     */
    private $apiClient;

    /**
     * @var object
     */
    private $databaseData;

    /**
     * Initial step
     */
    public function setUp()
    {
        $this->apiClient                = new DummyApiClientService();
        $this->currencyStockConverter   = new CurrencyStockConverter($this->apiClient,'GBP');
        $this->databaseData             = new DummyDatabaseData();
    }

    /**
     * Test converting single stock price
     */
    public function testConvertSingleStock()
    {
        $stock = 27.3;
        $convertedStock = $this->currencyStockConverter->convertSingleStock($stock);
        $this->assertEquals(24.64, $convertedStock);
        $this->assertTrue(gettype($convertedStock) === 'double');
    }

    public function testConvertStock()
    {
        $closingStocks = $this->databaseData->getClosingStockPriceData();
        $convertedClosingStocks = $this->currencyStockConverter->convertStocks($closingStocks);
        $this->assertNotEquals($closingStocks[0]['closing_price'],$convertedClosingStocks[0]['closing_price']);
    }
}