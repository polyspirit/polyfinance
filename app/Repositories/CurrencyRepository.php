<?php

namespace App\Repositories;

use App\Repositories\MainRepository;
use App\Models\Currency;

class CurrencyRepository extends MainRepository
{
    protected string $model = Currency::class;
}
