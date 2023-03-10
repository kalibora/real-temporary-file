<?php

namespace Kalibora\RealTemporaryFile;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use SplFileInfo;

/**
 * @method string getRealPath()
 */
class RealTemporaryDirectory extends SplFileInfo
{
    private bool $destructed = false;

    /**
     * See: https://stackoverflow.com/a/1707859
     */
    public function __construct(string $prefix = 'kalibora_real_tmp_')
    {
        $tempfile = tempnam(sys_get_temp_dir(), $prefix);

        if ($tempfile === false) {
            throw new RuntimeException('Cannot create temp file.');
        }

        if (file_exists($tempfile)) {
            unlink($tempfile);
        }

        mkdir($tempfile, $mode = 0777);

        if (!is_dir($tempfile)) {
            throw new RuntimeException('Cannot create directory. ' . $tempfile);
        }

        parent::__construct($tempfile);
    }

    /**
     * See: https://stackoverflow.com/a/3349792
     */
    public function __destruct()
    {
        if ($this->destructed) {
            return;
        }

        $it = new RecursiveDirectoryIterator($this->getRealPath(), RecursiveDirectoryIterator::SKIP_DOTS);

        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            assert($file instanceof SplFileInfo);
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        rmdir($this->getRealPath());

        $this->destructed = true;
    }
}
