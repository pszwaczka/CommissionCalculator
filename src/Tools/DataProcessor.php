<?php

namespace CommissionCalculator\Tools;

use CommissionCalculator\Output\OutputDestination;
use CommissionCalculator\DataTypes\DTO\Transaction;
use CommissionCalculator\Input\Transaction\TransactionProvider;


class DataProcessor
{
    public function __construct(
        protected readonly TransactionProvider $transactionProvider,
        protected readonly OutputDestination $outputDestination,
        protected readonly CommissionCalculator $commissionCalculator,
    ) {}

    public function processData(): void
    {
        $transactions = $this->transactionProvider->importData();
        /** @var Transaction $transaction */
        foreach ($transactions as $transaction) {
            $this->processRecord($transaction);
        }
    }

    protected function processRecord(Transaction $transaction): void
    {
        $commission = $this->commissionCalculator->calculate($transaction);
        $this->outputDestination->exportData($commission);
    }
}