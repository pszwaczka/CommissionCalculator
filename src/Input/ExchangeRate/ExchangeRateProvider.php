<?php

namespace CommissionCalculator\Input\ExchangeRate;

use CommissionCalculator\DataTypes\DTO\Currency;

interface ExchangeRateProvider
{
    public function getExchangeRate(Currency $currency): float;
}
