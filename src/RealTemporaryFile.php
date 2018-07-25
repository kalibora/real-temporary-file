<?php

namespace Kalibora\RealTemporaryFile;

class RealTemporaryFile extends \SplFileObject
{
    public function __construct(string $prefix = 'kalibora_real_tmp_')
    {
        $file = sys_get_temp_dir() . '/' . uniqid($prefix, true);

        parent::__construct($file, 'w+');
    }

    public function __destruct()
    {
        if ($this->getRealPath() === false) {
            return;
        }

        unlink($this->getRealPath());
    }
}
