<?php

use CommissionCalculator\Output\Console;
use CommissionCalculator\Tools\FileReader;
use CommissionCalculator\Tools\DataProcessor;
use CommissionCalculator\DataTypes\DTO\Currency;
use CommissionCalculator\Tools\CurrencyExchanger;
use CommissionCalculator\Tools\CommissionCalculator;
use CommissionCalculator\Input\Country\BinListDotNet;
use CommissionCalculator\Tools\EuropeanUnionValidator;
use CommissionCalculator\Input\Transaction\ImporterFromFile;
use CommissionCalculator\Input\ExchangeRate\EuroExchangeRateApiDotIO;
use CommissionCalculator\Input\CommissionRate\EuropeanUnionCommissionRate;


require __DIR__ . '/vendor/autoload.php';

$targetCurrency = Currency::fromAlpha3('EUR');
$imputFile = $argv[1];

$currencyExchanger = new CurrencyExchanger(
    targetCurrency: $targetCurrency,
    exchangeRateProvider: new EuroExchangeRateApiDotIO(),
);

$commissionRateProvider = new EuropeanUnionCommissionRate(
    countryProvider: new BinListDotNet(),
    europeanUnionValidator: new EuropeanUnionValidator(),
);

$commissionCalculator = new CommissionCalculator(
    commissionRateProvider: $commissionRateProvider,
    currencyExchanger: $currencyExchanger,
    targetCurrency: $targetCurrency,
);

$importerFromFile = new ImporterFromFile(
    filename: $imputFile,
    fileReader: new FileReader(),
);

$dataProccessor = new DataProcessor(
    transactionProvider: $importerFromFile,
    outputDestination: new Console(),
    commissionCalculator: $commissionCalculator,
);

$dataProccessor->processData();