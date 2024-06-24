<?php

namespace CommissionCalculator\Tools;

use CommissionCalculator\DataTypes\DTO\Currency;
use CommissionCalculator\DataTypes\DTO\MonetaryValue;
use CommissionCalculator\Input\ExchangeRate\ExchangeRateProvider;

class CurrencyExchanger
{
    public function __construct(
        protected Currency $targetCurrency,
        protected ExchangeRateProvider $exchangeRateProvider,
    ) {}

    public function exchange(MonetaryValue $baseAmount): MonetaryValue
    {
        $exchangeRate = $this->exchangeRateProvider->getExchangeRate($baseAmount->currency);
        $amountInTargetCurrency = $baseAmount->amount * $exchangeRate;
        return new MonetaryValue(
            amount: $amountInTargetCurrency, 
            currency: $this->targetCurrency
        );
    }
}
