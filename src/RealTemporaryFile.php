<?php

namespace Kalibora\RealTemporaryFile;

use Zend\Diactoros\UploadedFile;

/**
 * @method string getRealPath()
 */
class RealTemporaryFile extends \SplFileObject
{
    public const DEFAULT_PREFIX = 'kalibora_real_tmp_';

    private $destructed = false;

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
        if ($this->destructed) {
            return;
        }

        unlink($this->getRealPath());

        $this->destructed = true;
    }

    /**
     * Mimicry UploadedFile
     */
    public function toUploadedFile($clientFilename = null, $clientMediaType = null) : RealTemporaryUploadedFile
    {
        return new RealTemporaryUploadedFile($this, $clientFilename, $clientMediaType);
    }

    public static function createWithExtension(string $extension) : self
    {
        return new self(self::DEFAULT_PREFIX, $extension);
    }
}
