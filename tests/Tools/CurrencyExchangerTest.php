<?php
declare(strict_types=1);

namespace CommissionCalculator\Tests\Tools;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use CommissionCalculator\DataTypes\DTO\Currency;
use CommissionCalculator\Tools\CurrencyExchanger;
use CommissionCalculator\DataTypes\DTO\MonetaryValue;
use CommissionCalculator\Input\ExchangeRate\ExchangeRateProvider;

class CurrencyExchangerTest extends TestCase
{
    #[Test]
    #[TestWith([
        'baseAmount' => 100,
        'exchangeRate' => 1.1497,
    ])]
    #[TestWith([
        'baseAmount' => 170.33,    
        'exchangeRate' => 1.9999999,
    ])]
    #[TestWith([
        'baseAmount' => 0.21313,
        'exchangeRate' => 0.0909,
    ])]
    public function exchangePerformedCorrectly(
        float $baseAmount, 
        float $exchangeRate,
    )
    {
        $baseValue = new MonetaryValue(
            amount: $baseAmount,
            currency: Currency::fromAlpha3('USD'),
        );
        $targetCurrency = Currency::fromAlpha3('EUR');
        $exchangeRateProvider = $this->createMock(ExchangeRateProvider::class);
        $exchangeRateProvider
            ->method('getExchangeRate')
            ->with($baseValue->currency)
            ->willReturn($exchangeRate);
        $currencyExchanger = new CurrencyExchanger(
            $targetCurrency, 
            $exchangeRateProvider
        );

        $this->assertEquals(
            new MonetaryValue(
                amount: $baseValue->amount * $exchangeRate,
                currency: $targetCurrency,
            ),
            $currencyExchanger->exchange($baseValue)
        );
    }
}
