<?php

namespace CommissionCalculator\DataTypes\DTO;

use League\ISO3166\ISO3166;

readonly class Country
{
    public function __construct(
        public string $name,
        public string $alpha2,
        public string $alpha3,
        public string $numeric,
    ) {}

    public static function fromAlpha2(string $alpha2): self {
        [
            'name' => $name,
            'alpha2' => $alpha2,
            'alpha3' => $alpha3,
            'numeric' => $numeric,
        ] = (new ISO3166())->alpha2($alpha2);
        
        return new self(
            name: $name, 
            alpha2: $alpha2, 
            alpha3: $alpha3, 
            numeric: $numeric
        );
    }
}
