<?php
require_once "vendor/autoload.php";
require_once "service/ParametersParser.php";

use service\parametersParser;

$parametersParser = new parametersParser();
$parameters = $parametersParser->getParameters();


// show help and quit
if ($parameters->isHelp()) {
    echo $parametersParser->help();
    exit;
}

if (!$parameters->getInputPath() || !$parameters->getConfigPath() || !$parameters->getOutputPath()) {
    echo "Insufficient data " . PHP_EOL;
    echo $parametersParser->help();
    exit;
}

$delimiter = $parameters->getDelimiter();

//$inCsv = array_map(function ($value) use ($delimiter) {
//    return str_getcsv($value, $delimiter, "\"\"", "\\");
//}, file($parameters->getInputPath()));
//print_r($inCsv);

$inCsv = new SplFileObject($parameters->getInputPath());
$paramsInFile = $inCsv->getCsvControl();
//$inCsv->setFlags(SplFileObject::READ_CSV);
$file = new SplFileObject($parameters->getOutputPath(), 'w');

while (!$inCsv->eof()) {
    $file->fputcsv(array_map(function ($value) {
            return $value;
        }, $inCsv->fgetcsv())
        , $delimiter, $paramsInFile[1], $paramsInFile[2]);
}

print_r($inCsv->getCsvControl());
$encoding = mb_detect_encoding(file_get_contents($parameters->getInputPath()), 'UTF-8');
