<?php

namespace Kalibora\RealTemporaryFile;

class RealTemporaryFile extends \SplFileObject
{
    public const DEFAULT_PREFIX = 'kalibora_real_tmp_';

    public function __construct(string $prefix = self::DEFAULT_PREFIX, string $extension = null)
    {
        $file = sys_get_temp_dir() . '/' . str_replace('.', '_', uniqid($prefix, true));

        if ($extension) {
            $file .= ".{$extension}";
        }

        parent::__construct($file, 'w+');
    }

    public function __destruct()
    {
        if ($this->getRealPath() === false) {
            return;
        }

        unlink($this->getRealPath());
    }

    public static function createWithExtension(string $extension) : self
    {
        return new self(self::DEFAULT_PREFIX, $extension);
    }
}
