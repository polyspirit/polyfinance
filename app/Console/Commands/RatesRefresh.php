<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Classes\Currencies;

class RatesRefresh extends Command
{
    protected $signature = 'rates:refresh';
    protected $description = 'Refresh all currencies rates';

    public function handle()
    {
        Currencies::refreshRates();
    }
}
