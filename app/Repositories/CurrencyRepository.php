<?php

namespace App\Repositories;

use App\Repositories\MainRepository;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Model;

class CurrencyRepository extends MainRepository
{
    protected string $model = Currency::class;

    public function findByCode(string $code, $columns = ['*']): Currency
    {
        return $this->findBy('code', $code);
    }

    public function getDefaultCurrency(): Currency
    {
        return $this->findByCode(Currency::DEFAULT_CURRENCY_CODE);
    }
}
