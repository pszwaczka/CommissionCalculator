<?php

namespace CommissionCalculator\DataTypes\DTO;

use CommissionCalculator\DataTypes\DTO\Currency;
use CommissionCalculator\DataTypes\DTO\MonetaryValue;
use CommissionCalculator\DataTypes\DTO\BankIdentificationNumber;

readonly class Transaction
{
    public function __construct(
        public BankIdentificationNumber $bin,
        public MonetaryValue $amount,
    ) {
    }

    public static function fromJson(object $json): self
    {
        return new self(
            bin: new BankIdentificationNumber($json->bin),
            amount: new MonetaryValue(
                amount: $json->amount,
                currency: Currency::fromAlpha3($json->currency),
            ),
        );
    }
}
