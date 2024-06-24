<?php

namespace CommissionCalculator\Output;

use CommissionCalculator\DataTypes\DTO\MonetaryValue;

class Console implements OutputDestination
{  
    public function exportData(MonetaryValue $commission): void
    {
        echo (string)$commission->amount.PHP_EOL;
    }

}
