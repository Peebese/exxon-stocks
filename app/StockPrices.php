<?php

namespace App;


use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use \Illuminate\Database\Eloquent\Collection as EloquentCollection;
use App\Helpers\Stocks\CurrencyStockConverter as StockConverter;

class StockPrices extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date', 'closing_price',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Handles import of stock prices
     *
     * @throws Exception
     */
    public function importStockPrices() : void
    {
        $path = base_path(env('STOCK_PRICES_FILE','resources/xom_prices.csv'));

        if (!file_exists($path)) {
            throw new Exception('csv file was not found: ' . $path);
        }

        $insert_data = collect($this->createInsertDataArray($path));
        $chunks = $insert_data->chunk(500);
        $this->insertDataChunks($chunks);
    }

    /**
     * Inserts data chunks in table
     *
     * @param Collection $chunks
     */
    private function insertDataChunks(Collection $chunks) : void
    {
        foreach ($chunks as $chunk)
        {
            self::query()->insert($chunk->toArray());
        }
    }

    /**
     *
     * @param string $path
     * @return array
     */
    private function createInsertDataArray(string $path) : array
    {
        $header = true;
        $insert_data = [];
        $handle = fopen($path,"r");

        while ($csvLine = fgetcsv($handle, 1000, ",")) {

            if ($header) {
                $header = false;
                continue;
            }

            $insert_data[] = [
                'date'          => $csvLine[0],
                'closing_price' => $csvLine[1]
            ];
        }

        fclose($handle);

        return $insert_data;
    }

    public static function getCollection(string $dateFrom, string $dateTo) : EloquentCollection
    {
        $dateFrom   = date_format(date_create($dateFrom),'Y-m-d');
        $dateTo     = date_format(date_create($dateTo),'Y-m-d');

        return self::query()
            ->whereBetween('date',[$dateFrom, $dateTo])
            ->get(['date','closing_price']);
    }

    public static function formatCollectionReport(EloquentCollection $resultsArr, StockConverter $converter) : array
    {
        $avg = $resultsArr->avg('closing_price');
        $lowest = $resultsArr->sortBy('closing_price')->first();
        $highest = $resultsArr->sortBy('closing_price')->last();

        $avg = $converter->convertSingleStock($avg);
        $highest->closing_price = $converter->convertSingleStock($highest->closing_price);
        $lowest->closing_price = $converter->convertSingleStock($lowest->closing_price);

        return [
            'average' => $avg,
            'lowest'  => $lowest,
            'highest' => $highest,
        ];
    }
}