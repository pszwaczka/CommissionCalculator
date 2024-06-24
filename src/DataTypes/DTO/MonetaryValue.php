<?php

namespace CommissionCalculator\DataTypes\DTO;

use CommissionCalculator\DataTypes\DTO\Currency;

readonly class MonetaryValue
{
    public function __construct(
        public float $amount,
        public Currency $currency
    ) {
    }

    public function roundUp(int $precision = 2): self
    {
        $multiplier = 10 ** $precision;
        return new self(
            amount: ceil($this->amount * $multiplier) / $multiplier,
            currency: $this->currency
        );
    }
}
