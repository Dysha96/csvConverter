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
