<?php
declare(strict_types=1);

namespace CommissionCalculator\Tests\Tools;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use CommissionCalculator\DataTypes\DTO\Country;
use CommissionCalculator\Tools\EuropeanUnionValidator;

class EuropeanUnionValidatorTest extends TestCase
{
    #[Test]
    public function includesReturnsTrueForEU(): void
    {
        $validator = new EuropeanUnionValidator();
        $this->assertTrue($validator->includes(Country::fromAlpha2('PL')));
    }

    #[Test]
    public function includesReturnsFalseForNonEU(): void
    {
        $validator = new EuropeanUnionValidator();
        $this->assertFalse($validator->includes(Country::fromAlpha2('US')));
    }
}
