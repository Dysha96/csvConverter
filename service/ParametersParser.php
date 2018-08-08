<?php

namespace service;
require_once dirname(__DIR__) . '/domains/Parameters.php';
//require_once "../domains/Parameters.php";

use domains\Parameters;
use GetOpt\Argument;
use GetOpt\ArgumentException;
use GetOpt\ArgumentException\Missing;
use GetOpt\GetOpt;
use GetOpt\Option;
use SplFileInfo;

class parametersParser
{
    private $getOpt;

    private $isParsed = false;

    public function __construct()
    {
        $this->getOpt = new GetOpt();
    }

    private function initializationOption()
    {
        $this->getOpt->addOptions([

            Option::create('i', 'input', GetOpt::REQUIRED_ARGUMENT)
                ->setDescription('This you input file')
                ->setValidation(function ($arg) {
                    $info = new SplFileInfo($arg);
                    if ($info->getExtension() != 'csv') {
                        echo "FILE HAS AN INCORRECT FORMAT" . PHP_EOL;
                        return false;
                    }
                    if (!$info->isReadable()) {
                        echo "MISSING FILE OR NOT ENOUGH RIGHTS" . PHP_EOL;
                        return false;
                    }
                    return true;
                }),

            Option::create('c', 'config', GetOpt::REQUIRED_ARGUMENT)
                ->setDescription('This you config file')
                ->setValidation(function ($arg) {
                    $info = new SplFileInfo($arg);
                    if ($info->getExtension() != 'php') {
                        echo "FILE NOT CORRECTED" . PHP_EOL;
                        return false;
                    }
                    if (!$info->isReadable()) {
                        echo "MISSING FILE OR NOT ENOUGH RIGHTS" . PHP_EOL;
                        return false;
                    }
                    return true;
                }),

            Option::create('o', 'output', GetOpt::REQUIRED_ARGUMENT)
                ->setDescription('This you output file')
                ->setValidation(function ($arg) {
                    $info = new SplFileInfo($arg);
                    if ($info->getExtension() != 'csv') {
                        echo "FILE NOT CORRECTED" . PHP_EOL;
                        return false;
                    }
                    if (!$info->isWritable() && $info->isFile()) {
                        echo "NOT ENOUGH RIGHTS" . PHP_EOL;
                        return false;
                    }
                    return true;
                }),

            Option::create('d', 'delimiter', GetOpt::OPTIONAL_ARGUMENT)
                ->setDescription('Set delimiter [default: ,]')
                ->setArgument(new Argument(',', 'is_string', 'delimiter')),

            Option::create(null, 'skip-first', GetOpt::NO_ARGUMENT)
                ->setDescription('Skip the first line')
                ->setDefaultValue(false),

            Option::create(null, 'strict', GetOpt::NO_ARGUMENT)
                ->setDescription('Verify that the file contains the required number of columns')
                ->setDefaultValue(false),

            Option::create('h', 'help', GetOpt::NO_ARGUMENT)
                ->setDescription('Print help')
                ->setDefaultValue(false),

        ]);
    }

    private function parse()
    {
        try {
            try {
                $this->getOpt->process();
            } catch (Missing $exception) {
                // catch missing exceptions if help is requested
                if (!$this->getOpt->getOption('help')) {
                    throw $exception;
                }
            }
        } catch (ArgumentException $exception) {
            file_put_contents('php://stderr', $exception->getMessage() . PHP_EOL);
            echo PHP_EOL . $this->getOpt->getHelpText();
            exit(1);
        }
    }

    public function getParameters()
    {
        if (!$this->isParsed) {
            $this->initializationOption();
            $this->parse();
        }
        return new Parameters(
            $this->getOpt->getOption('input'),
            $this->getOpt->getOption('config'),
            $this->getOpt->getOption('output'),
            $this->getOpt->getOption('delimiter'),
            $this->getOpt->getOption('skip-first'),
            $this->getOpt->getOption('strict'),
            $this->getOpt->getOption('help')
        );
    }

    public function help()
    {
        if (!$this->isParsed) {
            $this->initializationOption();
            $this->parse();
        }
        return $this->getOpt->getHelpText();
    }

}
