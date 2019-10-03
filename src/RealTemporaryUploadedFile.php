<?php

namespace Kalibora\RealTemporaryFile;

use Zend\Diactoros\UploadedFile;

class RealTemporaryUploadedFile extends UploadedFile
{
    // Do not unlink
    private $tempFile;

    public function __construct(RealTemporaryFile $tempFile, $clientFilename = null, $clientMediaType = null)
    {
        $this->tempFile = $tempFile;

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
