<?php

use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    public function testIsAllOptions()
    {
        exec('php ../index.php -o out.csv -c confTest1.php', $output, $exitCode);
        $this->assertNotEquals(0, $exitCode);

        exec('php ../index.php -o out.csv', $output, $exitCode);
        $this->assertNotEquals(0, $exitCode);

        exec('php ../index.php -c confTest1.php', $output, $exitCode);
        $this->assertNotEquals(0, $exitCode);

        exec('php ../index.php --input inTest1.csv -c confTest1.php', $output, $exitCode);
        $this->assertNotEquals(0, $exitCode);
    }

    public function testIsHelp()
    {
        exec('php ../index.php -o out.csv -c confTest1.php --help', $output, $exitCode);
        $this->assertEquals(0, $exitCode);

        exec('php ../index.php -o out.csv -h', $output, $exitCode);
        $this->assertEquals(0, $exitCode);

        exec('php ../index.php -h', $output, $exitCode);
        $this->assertEquals(0, $exitCode);

        exec('php ../index.php --help', $output, $exitCode);
        $this->assertEquals(0, $exitCode);
    }

    public function testIsFile1()
    {
        exec('php ../index.php -i inTest1.csv -c confTest1.php -o out.csv', $output, $exitCode);
        $out = file_get_contents('out.csv');
        $outTest = file_get_contents('outTest1.1.csv');
        $this->assertEquals($out, $outTest);

        exec('php ../index.php -i inTest1.csv -c confTest2.php -o out.csv', $output, $exitCode);
        $out = file_get_contents('out.csv');
        $outTest = file_get_contents('outTest1.2.csv');
        $this->assertEquals($out, $outTest);

        exec('php ../index.php -i inTest2.csv -c confTest1.php -o out.csv', $output, $exitCode);
        $out = file_get_contents('out.csv');
        $outTest = file_get_contents('outTest2.1.csv');
        $this->assertEquals($out, $outTest);

        exec('php ../index.php -i inTest2.csv -c confTest2.php -o out.csv', $output, $exitCode);
        $out = file_get_contents('out.csv');
        $outTest = file_get_contents('outTest2.2.csv');
        $this->assertEquals($out, $outTest);
    }
}
