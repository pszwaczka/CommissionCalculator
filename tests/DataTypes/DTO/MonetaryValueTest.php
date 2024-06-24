<?php
declare(strict_types=1);

namespace CommissionCalculator\Tests\DataTypes\DTO;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use CommissionCalculator\DataTypes\DTO\Currency;
use CommissionCalculator\DataTypes\DTO\MonetaryValue;

class MonetaryValueTest extends TestCase
{
    #[Test]
    #[TestWith([
        'value' => 1.200001,
        'precision' => 2,
        'expectedValue' => 1.21,
    ])]
    #[TestWith([
        'value' => 1.200001,
        'precision' => 4,
        'expectedValue' => 1.2001,
    ])]
    #[TestWith([
        'value' => 0.99999,
        'precision' => 1,
        'expectedValue' => 1,
    ])]
    #[TestWith([
        'value' => 10.2134,
        'precision' => 2,
        'expectedValue' => 10.22,
    ])]
    #[TestWith([
        'value' => 2.21,
        'precision' => 4,
        'expectedValue' => 2.21,
    ])]
    #[TestWith([
        'value' => 1.8888,
        'precision' => 2,
        'expectedValue' => 1.89,
    ])]
    public function RoundsUpCorrectly(
        float $value,
        int $precision,
        float $expectedValue,
    ): void
    {
        $currency = Currency::fromAlpha3('EUR');
        $unrounded = new MonetaryValue(
            amount: $value,
            currency: $currency,
        );
        $expected = new MonetaryValue(
            amount: $expectedValue,
            currency: $currency,
        );
        $this->assertEquals(
            $expected,
            $unrounded->roundUp($precision)
        );
    }
}
