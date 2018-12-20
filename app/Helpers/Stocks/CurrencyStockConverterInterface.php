<?php


namespace App\Helpers\Stocks;

/**
 * Interface CurrencyStockConverterInterface
 * @package App\Helpers\Stocks
 */
interface CurrencyStockConverterInterface
{
    public function convertSingleStock(float $stockPrice) : float ;
}