<?php

declare(strict_types=1);

namespace App\Service;

class ExchangeRateFormatter
{
    public function formatRateResponse(array $rateData, string $table): array
    {
        $exchangeRate = $rateData['rates'][0];

        if ($table === ExchangeRateConstants::TABLE_C) {
            return $this->formatCommercialRate($rateData, $exchangeRate);
        }

        return $this->formatStandardRate($rateData, $exchangeRate);
    }

    private function formatCommercialRate(array $rateData, array $exchangeRate): array
    {
        return [
            'name' => $rateData['currency'],
            'code' => $rateData['code'],
            'sell' => number_format($exchangeRate['bid'] + 0.07, 4, '.', ''),
            'buy' => number_format($exchangeRate['ask'] - 0.05, 4, '.', ''),
        ];
    }

    private function formatStandardRate(array $rateData, array $exchangeRate): array
    {
        return [
            'name' => $rateData['currency'],
            'code' => $rateData['code'],
            'avg' => number_format($exchangeRate['mid'], 4, '.', ''),
            'sell' => number_format($exchangeRate['mid'] + 0.15, 4, '.', ''),
            'buy' => null,
        ];
    }
}
