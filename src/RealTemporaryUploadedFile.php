<?php

namespace Kalibora\RealTemporaryFile;

use Laminas\Diactoros\UploadedFile;

class RealTemporaryUploadedFile extends UploadedFile
{
    public function __construct(// Do not unlink
        private RealTemporaryFile $tempFile, ?string $clientFilename = null, ?string $clientMediaType = null)
    {
        assert($tempFile->getRealPath() !== false);

        parent::__construct(
            $tempFile->getRealPath(),
            $tempFile->getSize(),
            UPLOAD_ERR_OK,
            $clientFilename,
            $clientMediaType
        );
    }

    public function getRealTemporaryFile() : RealTemporaryFile
    {
        return $this->tempFile;
    }
}
