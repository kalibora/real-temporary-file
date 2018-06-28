<?php

namespace Kalibora\RealTemporaryFile;

use PHPUnit\Framework\TestCase;

class RealTemporaryFileTest extends TestCase
{
    public function testRealPathIsNotFalse()
    {
        $file = new RealTemporaryFile();

        $this->assertNotFalse($file->getRealPath());
    }

    public function testWriteAndRead()
    {
        $numbers = ['1', '2', '3'];
        $file = new RealTemporaryFile();

        foreach ($numbers as $number) {
            $file->fwrite($number . PHP_EOL);
        }

        foreach ($file as $idx => $line) {
            $actual = trim($line);

            if ($actual) {
                $this->assertEquals($numbers[$idx], $actual);
            }
        }
    }
}
