<?php

namespace domains;


class Parameters
{
    private $inputPath;
    private $configPath;
    private $outputPath;
    private $delimiter;
    private $skipFirst;
    private $strict;
    private $help;

    public function __construct($inputPath, $configPath, $outputPath, $delimiter, $skipFirst, $strict, $help)
    {
        $this->inputPath = $inputPath;
        $this->configPath = $configPath;
        $this->outputPath = $outputPath;
        $this->delimiter = $delimiter;
        $this->skipFirst = $skipFirst;
        $this->strict = $strict;
        $this->help = $help;
    }

    public function getInputPath()
    {
        return $this->inputPath;
    }

    public function getConfigPath()
    {
        return $this->configPath;
    }

    public function getOutputPath()
    {
        return $this->outputPath;
    }

    public function getDelimiter()
    {
        return $this->delimiter;
    }

    public function isSkipFirst()
    {
        return $this->skipFirst;
    }

    public function isStrict()
    {
        return $this->strict;
    }

    public function isHelp()
    {
        return $this->help;
    }

}
