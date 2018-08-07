<?php
require_once "vendor/autoload.php";
require_once "service/ParametersParser.php";
require_once "service/processingCsv.php";

use service\parametersParser;

$parametersParser = new parametersParser();
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
    exit;
}

$encoding = mb_detect_encoding(file_get_contents($parameters->getInputPath()));
// show help and quit
if ($encoding != 'UTF-8' && $encoding != 'cp1251') {
    echo "Incorrect encoding " . PHP_EOL;
    echo $parametersParser->help();
    exit;
}

$inCsv = new SplFileObject($parameters->getInputPath());
$inCsv->setFlags(SplFileObject::READ_CSV);
$delimiter = $parameters->getDelimiter();
$arrayConfig = require_once $parameters->getConfigPath();

if ($parameters->isStrict() && $inCsv->valid()) {
    $firstRowData = $inCsv->fgetcsv($delimiter);
    $latestIndexRowData = count($firstRowData);
    $latestIndexArrayConfig = max(array_keys($arrayConfig)) + 1;

    if ($latestIndexArrayConfig > $latestIndexRowData) {
        echo "The number of columns of the input file is less than in the config" . PHP_EOL;
        exit;
    }
    $inCsv->rewind();
}

$paramsInFile = $inCsv->getCsvControl();
$enclosure = $paramsInFile[1];
$escape = $paramsInFile[2];
$faker = Faker\Factory::create();
$outCsv = new SplFileObject($parameters->getOutputPath(), 'w');
iconv_set_encoding('output_encoding', $encoding);

while ($inCsv->valid()) {
    if ($inCsv->key() == 0 && $parameters->isSkipFirst()) {
        $processedRowData = $inCsv->fgetcsv($delimiter);
    } else {
        $processedRowData = $process($inCsv->fgetcsv($delimiter), $arrayConfig, $faker);
    }
    $record($outCsv, $processedRowData, $delimiter, $enclosure, $escape);
}
