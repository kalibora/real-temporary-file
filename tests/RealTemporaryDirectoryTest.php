<?php

namespace Kalibora\RealTemporaryFile;

use PHPUnit\Framework\TestCase;

class RealTemporaryDirectoryTest extends TestCase
{
    public function testRealPathIsNotFalse()
    {
        $dir = new RealTemporaryDirectory();

        $this->assertNotFalse($dir->getRealPath());
    }

    public function testIsDirectory()
    {
        $dir = new RealTemporaryDirectory();

        $this->assertTrue($dir->isDir());
    }

    public function testRemoveFiles()
    {
        $dir = new RealTemporaryDirectory();

        $dirPath = $dir->getRealPath();
        $filePath = $dir->getRealPath() . '/touch.txt';

        touch($filePath);

        $this->assertTrue(file_exists($filePath));
        $this->assertTrue(is_dir($dirPath));

        // Force destruction
        $dir->__destruct();

        $this->assertFalse(file_exists($filePath));
        $this->assertFalse(is_dir($dirPath));
    }
}
