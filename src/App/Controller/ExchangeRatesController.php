<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ExchangeRateService;
use App\Service\ExchangeRateFormatter;
use App\Service\ExchangeRateConstants;

class ExchangeRatesController extends AbstractController
{
    private ExchangeRateService $exchangeRateService;
    private ExchangeRateFormatter $formatter;

    public function __construct(ExchangeRateService $exchangeRateService, ExchangeRateFormatter $formatter)
    {
        $this->exchangeRateService = $exchangeRateService;
        $this->formatter = $formatter;
    }

    /**
     * @Route("/api/exchange-rates", methods={"GET"})
     */
    public function getRatesByDate(Request $request): JsonResponse
    {
        $date = $request->query->get('date') ?: date('Y-m-d');
        $currencies = [
            ExchangeRateConstants::CURRENCY_EUR,
            ExchangeRateConstants::CURRENCY_USD,
            ExchangeRateConstants::CURRENCY_CZK,
            ExchangeRateConstants::CURRENCY_IDR,
            ExchangeRateConstants::CURRENCY_BRL
        ];
        $data = [];

        foreach ($currencies as $currency) {
            $data[$currency] = $this->fetchRateForCurrency($currency, $date);
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/api/exchange-rates/today", methods={"GET"})
     */
    public function getTodayRates(): JsonResponse
    {
        $date = date('Y-m-d');
        $currencies = [
            ExchangeRateConstants::CURRENCY_EUR,
            ExchangeRateConstants::CURRENCY_USD,
            ExchangeRateConstants::CURRENCY_CZK,
            ExchangeRateConstants::CURRENCY_IDR,
            ExchangeRateConstants::CURRENCY_BRL
        ];
        $data = [];

        foreach ($currencies as $currency) {
            $data[$currency] = $this->fetchTodayRateForCurrency($currency, $date);
        }

        return new JsonResponse($data);
    }

    private function fetchTodayRateForCurrency(string $currency, string $date): array
    {
        $table = ExchangeRateConstants::CURRENCY_TABLE_MAP[$currency];
        $rateData = $this->exchangeRateService->getExchangeRate($table, $currency, $date);

        if ($rateData === null || (isset($rateData['status_code']) && $rateData['status_code'] === 404)) {
            return [
                'name' => null,
                'code' => null,
                'avg' => null,
                'sell' => null,
                'buy' => null,
            ];
        }

        return $this->formatter->formatRateResponse($rateData, $table);
    }

    private function fetchRateForCurrency(string $currency, string $date): ?array
    {
        $table = ExchangeRateConstants::CURRENCY_TABLE_MAP[$currency];
        $rateData = $this->exchangeRateService->getExchangeRate($table, $currency, $date);

        if ($rateData === null || (isset($rateData['status_code']) && $rateData['status_code'] === 404)) {
            return [
                'rates' => null,
                'error' => "Failed to fetch rates for $currency on $date."
            ];
        }

        return $this->formatter->formatRateResponse($rateData, $table);
    }

    private function getTestRate(string $currency, string $table, string $date): JsonResponse
    {
        $rateData = $this->exchangeRateService->getExchangeRate($table, $currency, $date);

        if ($rateData === null || empty($rateData['rates'])) {
            return new JsonResponse(['error' => "No data found for $currency."], 404);
        }

        return new JsonResponse($this->formatter->formatRateResponse($rateData, $table));
    }
}
