<?php

namespace CommissionCalculator\Output;

use CommissionCalculator\DataTypes\DTO\MonetaryValue;

interface OutputDestination
{
    public function exportData(MonetaryValue $commission): void;
}
