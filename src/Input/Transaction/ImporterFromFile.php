<?php

namespace CommissionCalculator\Input\Transaction;

use CommissionCalculator\Tools\FileReader;
use CommissionCalculator\DataTypes\DTO\Transaction;
use CommissionCalculator\Exceptions\DataStructureException;
use CommissionCalculator\Exceptions\FileException;
use CommissionCalculator\Exceptions\JsonException;

class ImporterFromFile implements TransactionProvider
{
    /**
     * @var resource
     */
    private mixed $file;
    public function __construct (
        string $filename, 
        protected readonly FileReader $fileReader,
    ) {
        $this->file = $this->fileReader->open($filename);
        if ($this->file === false) 
        {
            throw new FileException("Could not open file $filename");
        }
    }
    
    public function __destruct() {
        $this->fileReader->close($this->file);
    }

    public function importData(): \Generator
    {
        while ($line = $this->fileReader->readLine($this->file)) 
        {
            yield $this->parseLine($line);
        }
    }

    protected function parseLine(string $line): Transaction
    {
        $json = json_decode($line);
        if ($json === null) {
            throw new JsonException("Failed to parse JSON from line: $line");
        }
        if (!isset($json->bin)) {
            throw new DataStructureException("Missing 'bin' field in JSON: $line");
        }
        if (!isset($json->amount)) {
            throw new DataStructureException("Missing 'amount' field in JSON: $line");
        }
        if (!isset($json->currency)) {
            throw new DataStructureException("Missing 'currency' field in JSON: $line");
        }
        return Transaction::fromJson($json);
    }
}
