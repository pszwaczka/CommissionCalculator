<?php

namespace CommissionCalculator\Input\Country;

use CommissionCalculator\DataTypes\DTO\BankIdentificationNumber;
use CommissionCalculator\DataTypes\DTO\Country;

interface CountryProvider
{
    public function getCountry(BankIdentificationNumber $bin): Country;
}
