<?php

namespace App\Classes;

use AmrShawky\LaravelCurrency\Facade\Currency as CurrencyFacade;
use App\Repositories\CurrencyRepository;

/**
 * library: https://github.com/amrshawky/laravel-currency
 * service: https://exchangerate.host
 */
class Currencies
{
    public const USED_CURRENCIES = [
        'USD' => '$',
        'EUR' => '€',
        'GEL' => '₾',
        'RUB' => '₽',
        'AMD' => '֏'
    ];
    public const BASE_CURRENCY_CODE = 'USD';

    public static function getUsedCodes()
    {
        return array_keys(self::USED_CURRENCIES);
    }

    public static function refreshRates()
    {
        $currencyRepo = resolve(CurrencyRepository::class);

        $rates = CurrencyFacade::rates()
            ->latest()
            ->symbols(self::getUsedCodes())
            ->base(self::BASE_CURRENCY_CODE)
            ->get();

        foreach ($rates as $code => $rate) {
            $currency = $currencyRepo->findBy('code', $code);

            if (!is_null($currency)) {
                $currencyRepo->update(['rate', $rate], $currency->id);
            }
        }
    }

    public static function getRate(string $from, string $to): float
    {
        return CurrencyFacade::convert()
            ->from($from)
            ->to($to)
            ->throw()
            ->get();
    }

    public static function getRateFromBase(string $code): float
    {
        return self::getRate(self::BASE_CURRENCY_CODE, $code);
    }
}
