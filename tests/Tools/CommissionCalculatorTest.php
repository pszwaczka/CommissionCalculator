<?php
declare(strict_types=1);

namespace CommissionCalculator\Tests\Tools;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use CommissionCalculator\DataTypes\DTO\Currency;
use CommissionCalculator\Tools\CurrencyExchanger;
use CommissionCalculator\DataTypes\DTO\Transaction;
use CommissionCalculator\Tools\CommissionCalculator;
use CommissionCalculator\DataTypes\DTO\MonetaryValue;
use CommissionCalculator\DataTypes\DTO\BankIdentificationNumber;
use CommissionCalculator\Input\CommissionRate\CommissionRateProvider;


class CommissionCalculatorTest extends TestCase
{
    #[Test]
    #[TestWith([
        'inputAmount' => 100,
        'exchangeRate' => 1.1497,
        'commissionRate' => 0.02,
        'expectedCommissionValue' => 2.30,
    ])]
    #[TestWith([
        'inputAmount' => 100,
        'exchangeRate' => 1.000001,
        'commissionRate' => 0.03,
        'expectedCommissionValue' => 3.01,
    ])]
    #[TestWith([
        'inputAmount' => 100,
        'exchangeRate' => 0.999999,
        'commissionRate' => 0.09,
        'expectedCommissionValue' => 9,
    ])]
    public function calculatesCorrectCommissionWithCurrencyExchange(
        float $inputAmount,
        float $exchangeRate,
        float $commissionRate,
        float $expectedCommissionValue,
    )
    {
        $commissionRateProvider = $this->createMock(CommissionRateProvider::class);
        $currencyExchanger = $this->createMock(CurrencyExchanger::class);
        $inputCurrency = Currency::fromAlpha3('USD');
        $targetCurrency = Currency::fromAlpha3('EUR');
        $bin = new BankIdentificationNumber('45717360');
        $inputTransactionValue = new MonetaryValue(
            amount: $inputAmount,
            currency: $inputCurrency,
        );
        $exchangedTransactionValue = new MonetaryValue(
            amount: $inputAmount * $exchangeRate,
            currency: $targetCurrency,
        );
        $expectedCommission = new MonetaryValue(
            amount: $expectedCommissionValue,
            currency: $targetCurrency,
        );

        $transaction = new Transaction(
            bin: $bin,
            amount: $inputTransactionValue,
        );

        $commissionCalculator = new CommissionCalculator(
            $commissionRateProvider,
            $currencyExchanger,
            $targetCurrency,
        );

        $commissionRateProvider
            ->method('getCommissionRate')
            ->with($bin)
            ->willReturn($commissionRate);

        $currencyExchanger
            ->method('exchange')
            ->with($inputTransactionValue)
            ->willReturn($exchangedTransactionValue);

        $commission = $commissionCalculator->calculate($transaction);

        $this->assertEquals($expectedCommission, $commission);
    }

    #[Test]
    #[TestWith([
        'inputAmount' => 100,
        'commissionRate' => 0.02,
        'expectedCommissionValue' => 2,
    ])]
    #[TestWith([
        'inputAmount' => 100.000001,
        'commissionRate' => 0.01,
        'expectedCommissionValue' => 1.01,
    ])]
    #[TestWith([
        'inputAmount' => 100,
        'commissionRate' => 0.000009,
        'expectedCommissionValue' => 0.01,
    ])]
    public function calculatesCorrectCommissionWithoutCurrencyExchange(
        float $inputAmount,
        float $commissionRate,
        float $expectedCommissionValue,
    )
    {
        $commissionRateProvider = $this->createMock(CommissionRateProvider::class);
        $currencyExchanger = $this->createMock(CurrencyExchanger::class);
        $currency = Currency::fromAlpha3('EUR');
        $bin = new BankIdentificationNumber('45717360');
        $transactionValue = new MonetaryValue(
            amount: $inputAmount,
            currency: $currency,
        );
        $expectedCommission = new MonetaryValue(
            amount: $expectedCommissionValue,
            currency: $currency,
        );

        $transaction = new Transaction(
            bin: $bin,
            amount: $transactionValue,
        );

        $commissionCalculator = new CommissionCalculator(
            $commissionRateProvider,
            $currencyExchanger,
            targetCurrency: $currency,
        );

        $commissionRateProvider
            ->method('getCommissionRate')
            ->with($bin)
            ->willReturn($commissionRate);

        $commission = $commissionCalculator->calculate($transaction);

        $this->assertEquals($expectedCommission, $commission);
    }
}