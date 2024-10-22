<?php

namespace Integration\CurrencyRates;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CurrencyRatesApiTest extends WebTestCase
{
    public function testFetchTodayRates(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/exchange-rates/today');
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        if (isset($responseData['usd'])) {
            $this->assertArrayHasKey('sell', $responseData['usd']);
            $this->assertArrayHasKey('buy', $responseData['usd']);
        } else {
            $this->fail('USD data is missing in the response');
        }
    }

    public function testFetchHistoricalRates(): void
    {
        $client = static::createClient();
        $date = '2023-09-01';
        $client->request('GET', "/api/exchange-rates?date=$date");
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);

        if (isset($responseData['usd'])) {
            $this->assertArrayHasKey('sell', $responseData['usd']);
            $this->assertArrayHasKey('buy', $responseData['usd']);
        } else {
            $this->fail('USD data is missing for the provided date');
        }
    }
}
