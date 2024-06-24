<?php

namespace CommissionCalculator\DataTypes\DTO;

use Alcohol\ISO4217;

readonly class Currency
{
    public function __construct(
        public string $name,
        public string $alpha3,
        public string $numeric,
        public int $exp,
    ) {}

    public static function fromAlpha3(string $alpha3): self {
        [
            'name' => $name,
            'alpha3' => $alpha3,
            'numeric' => $numeric,
            'exp' => $exp,
        ] = (new ISO4217())->getByAlpha3($alpha3);

        return new self(
            name: $name, 
            alpha3: $alpha3, 
            numeric: $numeric, 
            exp: $exp
        );
    }
}
