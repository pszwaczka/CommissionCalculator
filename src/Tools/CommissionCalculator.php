<?php

namespace CommissionCalculator\Tools;

use CommissionCalculator\DataTypes\DTO\Currency;
use CommissionCalculator\DataTypes\DTO\Transaction;
use CommissionCalculator\DataTypes\DTO\MonetaryValue;
use CommissionCalculator\DataTypes\DTO\BankIdentificationNumber;
use CommissionCalculator\Input\CommissionRate\CommissionRateProvider;


class CommissionCalculator
{
    public function __construct(
        protected CommissionRateProvider $commissionRateProvider,
        protected CurrencyExchanger $currencyExchanger,
        protected Currency $targetCurrency,
    ) {
    }

    public function calculate(Transaction $transaction): MonetaryValue
    {
        $commissionBase = $this->getCommissionBase($transaction->amount);
        $commissionRate = $this->getCommissionRate($transaction->bin);
        $commission = $this->calculateCommission($commissionBase, $commissionRate);

        return $commission->roundUp(precision: 2);
    }

    protected function getCommissionBase(MonetaryValue $baseAmount): MonetaryValue
    {
        if ($baseAmount->currency != $this->targetCurrency) {
            return $this->currencyExchanger->exchange($baseAmount);
        }
        return $baseAmount;
    }

    protected function getCommissionRate(BankIdentificationNumber $bin): float
    {
        return $this->commissionRateProvider->getCommissionRate($bin);
    }   


    protected function calculateCommission(MonetaryValue $baseAmount, float $commissionRate): MonetaryValue
    {
        $commissionValue = $baseAmount->amount * $commissionRate;
        return new MonetaryValue(
            amount: $commissionValue,
            currency: $baseAmount->currency,
        );
    }
}
