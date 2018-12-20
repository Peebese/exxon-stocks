<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\StockPrices;

class ImportClosingPrices extends Command
{
    /**
     * Console command
     * @var string
     */
    protected $signature = 'import:prices';

    /**
     * Console command description
     * @var string
     */
    protected $description = 'Imports data from closing price csv file';

    /**
     * Execute console command
     */
    public function handle()
    {
        $this->info('Stock prices import started');

        (new StockPrices())->importStockPrices();

        $this->info('Import Completed');
    }
}