<?php

declare(strict_types=1);

namespace App\Service;

class ExchangeRateConstants
{
    public const TABLE_A = 'A';
    public const TABLE_C = 'C';

    public const CURRENCY_USD = 'usd';
    public const CURRENCY_EUR = 'eur';
    public const CURRENCY_CZK = 'czk';
    public const CURRENCY_IDR = 'idr';
    public const CURRENCY_BRL = 'brl';

    public const CURRENCY_TABLE_MAP = [
        self::CURRENCY_IDR => self::TABLE_A,
        self::CURRENCY_BRL => self::TABLE_A,
        self::CURRENCY_USD => self::TABLE_C,
        self::CURRENCY_EUR => self::TABLE_C,
        self::CURRENCY_CZK => self::TABLE_C,
    ];
}
