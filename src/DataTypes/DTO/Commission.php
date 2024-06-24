<?php

namespace CommissionCalculator\DTO;

use CommissionCalculator\DataTypes\DTO\MonetaryValue;
use CommissionCalculator\DataTypes\DTO\Transaction;

readonly class Commission
{
    public function __construct(
        public Transaction $transaction,
        public MonetaryValue $commissionAmount,
    ) {
    }
}
