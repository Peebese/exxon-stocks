<?php

namespace Tests;

/**
 * Mirrors database data for testing
 *
 * Class DummyDatabaseData
 */
class DummyDatabaseData
{
    public function getClosingStockPriceData() : array
    {
        return [
            [
                'date' => '2000-03-02',
                'closing_price' => 29.42
            ],
            [
                'date' => '2000-03-03',
                'closing_price' => 28.92
            ],
            [
                'date' => '2000-03-06',
                'closing_price' => 27.88
            ],
            [
                'date' => '2000-03-07',
                'closing_price' => 30.56
            ],
            [
                'date' => '2000-03-08',
                'closing_price' => 30.44
            ],
            [
                'date' => '2000-03-09',
                'closing_price' => 30.74
            ],
            [
                'date' => '2000-03-10',
                'closing_price' => 29.44
            ]
        ];
    }
}