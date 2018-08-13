<?php

require_once "vendor/autoload.php";

use Faker\Factory;
use services\ParametersParser;
use function services\process;
use function services\record;
use function services\transcoding;

$parametersParser = new ParametersParser();
$parameters = $parametersParser->getParameters();

// show help and quit
if ($parameters->isHelp()) {
    echo $parametersParser->help();
    exit;
}
// show help and quit
if (!$parameters->getInputPath() || !$parameters->getConfigPath() || !$parameters->getOutputPath()) {
    echo "Insufficient data " . PHP_EOL;
    echo $parametersParser->help();
    exit(1);
}

if ($parameters->getInputPath() == $parameters->getOutputPath()) {
    echo "Files match" . PHP_EOL;
    echo $parametersParser->help();
    exit(1);
}

$encoding = mb_detect_encoding(file_get_contents($parameters->getInputPath()), array('UTF-8', 'Windows-1251'));
// show help and quit
if ($encoding != 'UTF-8' && $encoding != 'Windows-1251') {
    echo "Incorrect encoding " . PHP_EOL;
    exit(1);
}

$inCsv = new SplFileObject($parameters->getInputPath());
$inCsv->setFlags(SplFileObject::READ_CSV);
$delimiter = $parameters->getDelimiter();
if (strlen($delimiter) != 1) {
    echo "Wrong delimiter" . PHP_EOL;
    exit(1);
}
$arrayConfig = require_once $parameters->getConfigPath();

if ($parameters->isStrict() && $inCsv->valid()) {
    $firstRowData = $inCsv->fgetcsv($delimiter);
    $latestIndexRowData = count($firstRowData);
    $latestIndexArrayConfig = max(array_keys($arrayConfig)) + 1;

    if ($latestIndexArrayConfig > $latestIndexRowData) {
        echo "The number of columns of the input file is less than in the config" . PHP_EOL;
        exit(1);
    }
    $inCsv->rewind();
}

$paramsInFile = $inCsv->getCsvControl();
$enclosure = $paramsInFile[1];
$escape = $paramsInFile[2];
$faker = Factory::create();
$outCsv = new SplFileObject($parameters->getOutputPath(), 'w');
iconv_set_encoding('output_encoding', $encoding);
$numberColumns = 0;

while ($inCsv->valid()) {
    $rowData = $inCsv->fgetcsv($delimiter);
    if ($inCsv->key() == 0) {
        if ($parameters->isSkipFirst()) {
            $processedRowData = $rowData;
        } else {
            $processedRowData = process($rowData, $arrayConfig, $faker, $inCsv->key());
        }
        $numberColumns = sizeof($rowData);
    } else {
        if (sizeof($rowData) != $numberColumns) {
            echo "File does not meet the standard, processing is interrupted" . PHP_EOL;
            exit(1);
        }
        $processedRowData = process($rowData, $arrayConfig, $faker, $inCsv->key());
    }
    $encodingRowData = transcoding($processedRowData);
    record($outCsv, $encodingRowData, $delimiter, $enclosure, $escape);
}

$fp = fopen($parameters->getOutputPath(), "r");
$stat = fstat($fp);
$outCsv->ftruncate($stat['size'] - 1);
