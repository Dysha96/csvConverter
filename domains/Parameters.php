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

    /**
     * Parameters constructor.
     * @param $inputPath
     * @param $configPath
     * @param $outputPath
     * @param $delimiter
     * @param $skipFirst
     * @param $strict
     * @param $help
     */

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

    /**
     * @return mixed
     */
    public function getInputPath()
    {
        return $this->inputPath;
    }

    /**
     * @return mixed
     */
    public function getConfigPath()
    {
        return $this->configPath;
    }

    /**
     * @return mixed
     */
    public function getOutputPath()
    {
        return $this->outputPath;
    }

    /**
     * @return mixed
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * @return mixed
     */
    public function getSkipFirst()
    {
        return $this->skipFirst;
    }

    /**
     * @return mixed
     */
    public function getStrict()
    {
        return $this->strict;
    }

    /**
     * @return mixed
     */
    public function isHelp()
    {
        return ($this->help) ? true : false;
    }


}
