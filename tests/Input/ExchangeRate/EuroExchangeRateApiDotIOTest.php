<?php
declare(strict_types=1);

namespace CommissionCalculator\Tests\Input\ExchangeRate;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use CommissionCalculator\Tools\HttpClient;
use CommissionCalculator\DataTypes\DTO\Currency;
use CommissionCalculator\exceptions\HttpException;
use CommissionCalculator\exceptions\JsonException;
use CommissionCalculator\exceptions\DataStructureException;
use CommissionCalculator\exceptions\MissingDataException;
use CommissionCalculator\Input\ExchangeRate\EuroExchangeRateApiDotIO;

class EuroExchangeRateApiDotIOTest extends TestCase
{
    #[Test]
    public function getsCorrectExchangeRatesSendAsFloats(): void
    {
        $httpClient = $this->createMock(HttpClient::class);
        $httpClient
            ->method('get')
            ->willReturn('{"rates":{"USD":1.2,"PLN":4.0}}');
        $exchangeRateProvider = new EuroExchangeRateApiDotIO($httpClient);

        $this->assertEquals(1.2, $exchangeRateProvider->getExchangeRate(Currency::fromAlpha3('USD')));
        $this->assertEquals(4.0, $exchangeRateProvider->getExchangeRate(Currency::fromAlpha3('PLN')));
    }

    #[Test]
    public function getsCorrectExchangeRatesSendAsStrings(): void
    {
        $httpClient = $this->createMock(HttpClient::class);
        $httpClient
            ->method('get')
            ->willReturn('{"rates":{"USD":"1.2","PLN":"4.0"}}');
        $exchangeRateProvider = new EuroExchangeRateApiDotIO($httpClient);

        $this->assertEquals(1.2, $exchangeRateProvider->getExchangeRate(Currency::fromAlpha3('USD')));
        $this->assertEquals(4.0, $exchangeRateProvider->getExchangeRate(Currency::fromAlpha3('PLN')));
    }

    #[Test]
    public function throwsExceptionWhenExchangeRateNotFound(): void
    {
        $httpClient = $this->createMock(HttpClient::class);
        $httpClient
            ->method('get')
            ->willReturn('{"rates":{"USD":1.2,"PLN":4.0}}');
        $exchangeRateProvider = new EuroExchangeRateApiDotIO($httpClient);

        $this->expectException(MissingDataException::class);
        $exchangeRateProvider->getExchangeRate(Currency::fromAlpha3('EUR'));
    }

    #[Test]
    public function throwsExceptionWhenFailedToFetchData(): void
    {
        $httpClient = $this->createMock(HttpClient::class);
        $httpClient
            ->method('get')
            ->willReturn(false);
        $this->expectException(HttpException::class);
        $exchangeRateProvider = new EuroExchangeRateApiDotIO($httpClient);
    }

    #[Test]
    public function throwsExceptionWhenFailedToParseJson(): void
    {
        $httpClient = $this->createMock(HttpClient::class);
        $httpClient
            ->method('get')
            ->willReturn('invalid json');
        $this->expectException(JsonException::class);
        $exchangeRateProvider = new EuroExchangeRateApiDotIO($httpClient);
    }

    #[Test]
    public function throwsExceptionWhenFailedToParseRates(): void
    {
        $httpClient = $this->createMock(HttpClient::class);
        $httpClient
            ->method('get')
            ->willReturn('{"noRates":{"USD":1.2,"PLN":4.0}}');
        $this->expectException(DataStructureException::class);
        $exchangeRateProvider = new EuroExchangeRateApiDotIO($httpClient);
    }
}
