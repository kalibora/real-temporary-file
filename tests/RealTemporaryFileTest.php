<?php

namespace Kalibora\RealTemporaryFile;

use Laminas\Diactoros\UploadedFile;
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

    public function testFilename()
    {
        $file = new RealTemporaryFile();

        $this->assertMatchesRegularExpression('/^kalibora_real_tmp_.*$/', $file->getFilename());
        $this->assertSame('', $file->getExtension());
    }

    public function testFilenameWithExtension()
    {
        $file = RealTemporaryFile::createWithExtension('txt');

        $this->assertMatchesRegularExpression('/^kalibora_real_tmp_.*\.txt$/', $file->getFilename());
        $this->assertSame('txt', $file->getExtension());
    }

    public function testToUploadedFile()
    {
        $file = new RealTemporaryFile();

        $uploadedFile = $file->toUploadedFile();

        $this->assertInstanceOf(UploadedFile::class, $uploadedFile);
    }

    public function testRemoveFile()
    {
        $file = new RealTemporaryFile();

        $filePath = $file->getRealPath();

        $this->assertTrue(file_exists($filePath));

        // Force destruction
        $file->__destruct();

        $this->assertFalse(file_exists($filePath));
    }
}
