<?php

namespace App\Services\DataPersistence;

use App\Jobs\DataPersistence;
use App\Services\DataPersistence\Implements\HydratableRepository;
use App\Services\DataPersistence\Implements\UploadableInterface;

class DataPersistenceService implements UploadableInterface
{
    private string $fileType;
    private string $filePath;

    public const string UPLOAD_FILE_NAME    =   'airports';
    public const string UPLOAD_FILE_PATH    =   'tmp';

    public function prepareHydrate(HydratableRepository $repository): void
    {
        dispatch((new DataPersistence($repository, $this->filePath, $this->fileType)))->onQueue('{data-persistence}');
    }

    public function uploadFile($file): bool
    {
        try {
            $this->fileType = $file->getClientOriginalExtension();

            $this->filePath = $file->storeAs(
                self::UPLOAD_FILE_PATH,
                sprintf('%s.%s', uuid_create(UUID_TYPE_RANDOM), $this->fileType)
            );

            return true;
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());
        }
        return false;
    }
}
