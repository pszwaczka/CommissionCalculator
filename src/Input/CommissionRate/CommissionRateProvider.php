<?php

namespace CommissionCalculator\Input\CommissionRate;

use CommissionCalculator\DataTypes\DTO\BankIdentificationNumber;

interface CommissionRateProvider
{
    public function getCommissionRate(BankIdentificationNumber $bin): float;
}
