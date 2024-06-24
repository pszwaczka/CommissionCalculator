<?php

namespace CommissionCalculator\Tools;

use CommissionCalculator\DataTypes\DTO\Country;

class EuropeanUnionValidator
{
    protected const array EU_COUNTRIES = [
        'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI',
        'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT',
        'NL', 'PL', 'PT', 'RO', 'SE', 'SI', 'SK',
    ];

    public function includes(Country $country): bool
    {
        return in_array(
            needle: $country->alpha2, 
            haystack: self::EU_COUNTRIES
        );  
    }
}
