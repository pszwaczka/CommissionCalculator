<?php
declare(strict_types=1);

namespace CommissionCalculator\Tests\Input\CommissionRate;

use CommissionCalculator\DataTypes\DTO\BankIdentificationNumber;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use CommissionCalculator\DataTypes\DTO\Country;
use CommissionCalculator\Tools\EuropeanUnionValidator;
use CommissionCalculator\Input\Country\CountryProvider;
use CommissionCalculator\Input\CommissionRate\EuropeanUnionCommissionRate;

class EuropeanUnionCommissionRateTest extends TestCase
{
    #[Test]
    public function providesCorrectCommissionRateForEUCountry(): void
    {
        $bin = new BankIdentificationNumber('45717360');
        $countryProvider = $this->createMock(CountryProvider::class);
        $countryProvider
            ->method('getCountry')
            ->with($bin)
            ->willReturn(Country::fromAlpha2('PL'));

        $europeanUnionValidator = $this->createMock(EuropeanUnionValidator::class);
        $europeanUnionValidator
            ->method('includes')
            ->with(Country::fromAlpha2('PL'))
            ->willReturn(true);

        $euCommissionRateValue = 0.5;
        $nonEuCommissionRateValue = 0.7;

        $euCommissionRate = new EuropeanUnionCommissionRate(
            countryProvider: $countryProvider,
            europeanUnionValidator: $europeanUnionValidator,
            euCommissionRateValue: $euCommissionRateValue,
            nonEuCommissionRateValue: $nonEuCommissionRateValue,
        );

        $this->assertEquals(
            $euCommissionRate->getCommissionRate($bin),
            $euCommissionRateValue,
        );
    }

    #[Test]
    public function providesCorrectCommissionRateForNonEUCountry(): void
    {
        $bin = new BankIdentificationNumber('45717360');
        $countryProvider = $this->createMock(CountryProvider::class);
        $countryProvider
            ->method('getCountry')
            ->with($bin)
            ->willReturn(Country::fromAlpha2('PL'));

        $europeanUnionValidator = $this->createMock(EuropeanUnionValidator::class);
        $europeanUnionValidator
            ->method('includes')
            ->with(Country::fromAlpha2('PL'))
            ->willReturn(false);

        $euCommissionRateValue = 0.5;
        $nonEuCommissionRateValue = 0.7;

        $euCommissionRate = new EuropeanUnionCommissionRate(
            countryProvider: $countryProvider,
            europeanUnionValidator: $europeanUnionValidator,
            euCommissionRateValue: $euCommissionRateValue,
            nonEuCommissionRateValue: $nonEuCommissionRateValue,
        );

        $this->assertEquals(
            $euCommissionRate->getCommissionRate($bin),
            $nonEuCommissionRateValue,
        );
    }
  
}
