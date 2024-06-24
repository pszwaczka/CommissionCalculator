<?php

namespace CommissionCalculator\Input\CommissionRate;

use CommissionCalculator\DataTypes\DTO\BankIdentificationNumber;
use CommissionCalculator\DataTypes\DTO\Country;
use CommissionCalculator\Input\Country\CountryProvider;
use CommissionCalculator\Tools\EuropeanUnionValidator;

class EuropeanUnionCommissionRate implements CommissionRateProvider
{
  
    protected const float DEFAULT_EU_COMMISSION_RATE_VALUE = 0.01;
    protected const float DEFAULT_NON_EU_COMMISSION_RATE_VALUE = 0.02;

    public function __construct(
        protected readonly CountryProvider $countryProvider,
        protected readonly EuropeanUnionValidator $europeanUnionValidator,
        protected readonly float $euCommissionRateValue = self::DEFAULT_EU_COMMISSION_RATE_VALUE,
        protected readonly float $nonEuCommissionRateValue = self::DEFAULT_NON_EU_COMMISSION_RATE_VALUE,
    ) {}
    public function getCommissionRate(BankIdentificationNumber $bin): float
    {
        $country = $this->countryProvider->getCountry($bin);
        
        if ($this->europeanUnionValidator->includes($country)) {
            return $this->euCommissionRateValue;
        }
        return $this->nonEuCommissionRateValue;
    }
}
