<?php
declare(strict_types=1);

namespace CommissionCalculator\Tests\DataTypes\DTO;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use CommissionCalculator\DataTypes\DTO\Currency;
use CommissionCalculator\DataTypes\DTO\Transaction;
use CommissionCalculator\DataTypes\DTO\MonetaryValue;
use CommissionCalculator\DataTypes\DTO\BankIdentificationNumber;

class TransactionTest extends TestCase
{
    #[Test]
    public function testFromJson(): void
    {
        $json = '{"bin":"45717360","amount":100.001,"currency":"USD"}';
        $jsonObject = json_decode($json);
        $transaction = Transaction::fromJson($jsonObject);
        $this->assertEquals(
            new Transaction(
                bin: new BankIdentificationNumber('45717360'),
                amount: new MonetaryValue(
                    amount: 100.001,
                    currency: Currency::fromAlpha3('USD'),
                ),
            ),
            $transaction
        );
    }
}
