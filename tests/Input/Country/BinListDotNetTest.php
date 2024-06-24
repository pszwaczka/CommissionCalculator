<?php
declare(strict_types=1);

namespace CommissionCalculator\Tests\Input\Country;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use CommissionCalculator\Tools\HttpClient;
use PHPUnit\Framework\Attributes\TestWith;
use CommissionCalculator\DataTypes\DTO\Country;
use CommissionCalculator\exceptions\HttpException;
use CommissionCalculator\exceptions\JsonException;
use CommissionCalculator\Input\Country\BinListDotNet;
use CommissionCalculator\exceptions\DataStructureException;
use CommissionCalculator\DataTypes\DTO\BankIdentificationNumber;

class BinListDotNetTest extends TestCase
{
    #[Test]
    #[TestWith(['PL'])]
    #[TestWith(['US'])]
    #[TestWith(['DE'])]
    #[TestWith(['BR'])]
    public function getsCorrectCountry(string $alpha2CoutryCode): void
    {
        $httpClient = $this->createMock(HttpClient::class);
        $httpClient
            ->method('get')
            ->willReturn('{"country":{"alpha2":"'.$alpha2CoutryCode.'"}}');
        $binListDotNet = new BinListDotNet($httpClient);

        $this->assertEquals(
            Country::fromAlpha2($alpha2CoutryCode), 
            $binListDotNet->getCountry(new BankIdentificationNumber('123456'))
        );
    }   

    #[Test]
    public function throwsExceptionWhenCountryIsNotReturned(): void
    {
        $httpClient = $this->createMock(HttpClient::class);
        $httpClient
            ->method('get')
            ->willReturn('{"country":{}}');
        $binListDotNet = new BinListDotNet($httpClient);

        $this->expectException(DataStructureException::class);
        $binListDotNet->getCountry(new BankIdentificationNumber('123456'));
    }

    #[Test]
    public function throwsExceptionWhenHttpClientThrowsException(): void
    {
        $httpClient = $this->createMock(HttpClient::class);
        $httpClient
            ->method('get')
            ->willThrowException(new HttpException());
        $binListDotNet = new BinListDotNet($httpClient);

        $this->expectException(HttpException::class);
        $binListDotNet->getCountry(new BankIdentificationNumber('123456'));
    }

    #[Test]
    public function throwsExceptionWhenHttpClientReturnsInvalidJson(): void
    {
        $httpClient = $this->createMock(HttpClient::class);
        $httpClient
            ->method('get')
            ->willReturn('invalid json');
        $binListDotNet = new BinListDotNet($httpClient);

        $this->expectException(JsonException::class);
        $binListDotNet->getCountry(new BankIdentificationNumber('123456'));
    }
}