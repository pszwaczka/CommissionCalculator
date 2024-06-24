<?php

namespace CommissionCalculator\Input\Country;

use CommissionCalculator\Tools\HttpClient;
use CommissionCalculator\DataTypes\DTO\Country;
use CommissionCalculator\Exceptions\JsonException;
use CommissionCalculator\Input\Country\CountryProvider;
use CommissionCalculator\DataTypes\DTO\BankIdentificationNumber;
use CommissionCalculator\Exceptions\DataStructureException;
use CommissionCalculator\Exceptions\HttpException;

class BinListDotNet implements CountryProvider
{
    protected const string API_URL = 'https://lookup.binlist.net/';

    public function __construct(
        protected readonly HttpClient $httpClient,
    ) {}

    public function getCountry(BankIdentificationNumber $bin): Country
    {
        $response = $this->makeRequest($bin);
        $parsedResponse = $this->parseResponse($response);
        $countryCode = $parsedResponse->country->alpha2 
            ?? throw new DataStructureException(
                "Failed to parse country alpha2-code from JSON response from " . self::API_URL
            );
        return Country::fromAlpha2($countryCode);
    }
    
    protected function makeRequest(BankIdentificationNumber $bin): string
    {
        $requestUrl = self::API_URL . $bin->value;
        $response = $this->httpClient->get($requestUrl);
        if ($response === false) {
            throw new HttpException("Failed to fetch data from $requestUrl");
        }
        return $response;
    }

    protected function parseResponse(string $response): object
    {
        $parsedResponse = json_decode(
            json: $response, 
        );
        if ($parsedResponse === null) {
            throw new JsonException("Failed to parse JSON response from " . self::API_URL);
        }
        return $parsedResponse;
    }
}
