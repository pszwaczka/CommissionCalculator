<?php

namespace CommissionCalculator\Input\Transaction;

interface TransactionProvider
{
    public function importData(): \Generator;
}
