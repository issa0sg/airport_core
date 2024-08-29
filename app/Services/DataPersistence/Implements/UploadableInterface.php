<?php

namespace App\Services\DataPersistence\Implements;

interface UploadableInterface
{
    public function uploadFile($file): bool;
}
