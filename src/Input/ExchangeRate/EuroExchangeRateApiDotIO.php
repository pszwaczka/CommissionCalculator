<?php

namespace CommissionCalculator\Input\ExchangeRate;

use CommissionCalculator\Tools\HttpClient;
use CommissionCalculator\DataTypes\DTO\Currency;
use CommissionCalculator\Exceptions\DataStructureException;
use CommissionCalculator\Exceptions\HttpException;
use CommissionCalculator\Exceptions\JsonException;
use CommissionCalculator\Exceptions\MissingDataException;

class EuroExchangeRateApiDotIO implements ExchangeRateProvider
{
    protected const string API_URL = 'https://api.exchangeratesapi.io/latest';
    protected readonly object $exchangeRates;

    public function __construct(
        protected readonly HttpClient $httpClient,
    ) {
        $this->exchangeRates = $this->fetchExchangeRates();
    }

    public function getExchangeRate(Currency $currency): float
    {
        return $this->exchangeRates->{$currency->alpha3} 
            ?? throw new MissingDataException("Exchange rate for currency {$currency->alpha3} not found");
    }

    protected function fetchExchangeRates(): object
    {
        $apiResponse = $this->httpClient->get(self::API_URL);
        if ($apiResponse === false) {
            throw new HttpException("Failed to fetch data from " . self::API_URL);
        }
        $parsedResponse = json_decode($apiResponse);
        if ($parsedResponse === null) {
            throw new JsonException("Failed to parse JSON response from " . self::API_URL);
        }
        if (!isset($parsedResponse->rates)) {
            throw new DataStructureException("Failed to parse rates from JSON response from " . self::API_URL);
        }
        return $parsedResponse->rates;
    }
}
