<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Repositories\CurrencyRepository;
use App\Classes\Currencies;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencyRepo = resolve(CurrencyRepository::class);

        foreach (Currencies::USED_CURRENCIES as $code => $symbol) {
            $rate = Currencies::getRateFromBase($code);
            $currencyRepo->findOrCreate('code', $code, [
                'rate' => $rate,
                'symbol' => $symbol
            ]);
        }
    }
}
