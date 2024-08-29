<?php

namespace App\Services\DataPersistence\Implements;

interface Hydratable
{
    public function hydrate(HydratableRepository $repository, string $filePath);
}
