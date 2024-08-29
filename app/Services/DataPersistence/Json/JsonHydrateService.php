<?php

namespace App\Services\DataPersistence\Json;

use App\Services\DataPersistence\Implements\Hydratable;
use App\Services\DataPersistence\Implements\HydratableRepository;
use Exception;
use Illuminate\Support\Facades\Storage;

class JsonHydrateService implements Hydratable
{
    private const int DEFAULT_CHUNK_BYTE_SIZE   =   8192;
    public function hydrate(HydratableRepository $repository, string $filePath): bool
    {
        $fullPath = Storage::path($filePath);

        if (!(file_exists($fullPath) || is_readable($fullPath))) {
            throw new Exception('File not found or not readable');
        }

        $reader = fopen($fullPath, 'r');
        if ($reader === false) {
            throw new Exception('Could not open file');
        }

        try {

            while (!feof($reader)) {
                $buffer = fread($reader, self::DEFAULT_CHUNK_BYTE_SIZE);

                if (!str_ends_with($buffer, "\n")) {
                    $buffer .= fgets($reader);
                }

                $lines = explode("\n", $buffer);
                $repository->hydrate($lines);
            }
            return true;
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());
        } finally {
            fclose($reader);
        }
        return false;
    }
}
