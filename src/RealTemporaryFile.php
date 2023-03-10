<?php

namespace Kalibora\RealTemporaryFile;

use Zend\Diactoros\UploadedFile;

/**
 * @method string getRealPath()
 */
class RealTemporaryFile extends \SplFileObject
{
    public const DEFAULT_PREFIX = 'kalibora_real_tmp_';

    private bool $destructed = false;

    private static bool $init = false;

    /** @var array<string> */
    private static array $removePaths = [];

    public function __construct(string $prefix = self::DEFAULT_PREFIX, string $extension = null, bool $orphanRemoval = true)
    {
        self::initialize();

        $file = sys_get_temp_dir() . '/' . str_replace('.', '_', uniqid($prefix, true));

        if ($extension) {
            $file .= ".{$extension}";
        }

        parent::__construct($file, 'w+');

        if (!$orphanRemoval) {
            $this->destructed = true;
            self::$removePaths[] = $this->getRealPath();
        }
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
    public function toUploadedFile(?string $clientFilename = null, ?string $clientMediaType = null) : RealTemporaryUploadedFile
    {
        return new RealTemporaryUploadedFile($this, $clientFilename, $clientMediaType);
    }

    public static function createWithExtension(string $extension, bool $orphanRemoval = true) : self
    {
        return new self(self::DEFAULT_PREFIX, $extension, $orphanRemoval);
    }

    private static function initialize() : void
    {
        if (self::$init) {
            return;
        }

        register_shutdown_function(function () {
            foreach (self::$removePaths as $removePath) {
                if (!file_exists($removePath)) {
                    continue;
                }

                unlink($removePath);
            }
        });

        self::$init = true;
    }
}
