<?php
declare(strict_types=1);

namespace CommissionCalculator\Tests\Input\Transaction;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use CommissionCalculator\Tools\FileReader;
use CommissionCalculator\DataTypes\DTO\Currency;
use CommissionCalculator\DataTypes\DTO\Transaction;
use CommissionCalculator\DataTypes\DTO\MonetaryValue;
use CommissionCalculator\Input\Transaction\ImporterFromFile;
use CommissionCalculator\DataTypes\DTO\BankIdentificationNumber;

class ImporterFromFileTest extends TestCase
{
    #[Test]
    public function extractTransactonsFromFile(): void
    {
        $filename = 'input.txt';
        $fileReader = $this->createMock(FileReader::class);
        $fileReader
            ->expects($this->once())
            ->method('open')
            ->with($filename)
            ->willReturn(fopen('php://memory', 'r'));
        $fileReader
            ->expects($this->once())
            ->method('close');
        $fileReader->expects($this->exactly(3))
            ->method('readLine')
            ->willReturnOnConsecutiveCalls(
                '{"bin":"123456","amount":100.0,"currency":"EUR"}',
                '{"bin":"654321","amount":200.0,"currency":"USD"}',
                false
            );
        $importer = new ImporterFromFile($filename, $fileReader);
        $transactions = iterator_to_array($importer->importData());

        $this->assertCount(2, $transactions);
        $this->assertEquals(
            new Transaction(
                new BankIdentificationNumber('123456'), 
                new MonetaryValue(
                    amount: 100.0,
                    currency: Currency::fromAlpha3('EUR'),
                ),
            ),
            $transactions[0]
        );
        $this->assertEquals(
            new Transaction(
                new BankIdentificationNumber('654321'), 
                new MonetaryValue(
                    amount: 200.0,
                    currency: Currency::fromAlpha3('USD'),
                ),
            ),
            $transactions[1]
        );
    }
}
