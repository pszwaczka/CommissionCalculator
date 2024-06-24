<?php

namespace CommissionCalculator\DataTypes\DTO;

readonly class BankIdentificationNumber
{
    public function __construct(
        public string $value
    ) {
    }
}
