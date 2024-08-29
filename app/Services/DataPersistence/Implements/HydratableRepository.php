<?php

namespace App\Services\DataPersistence\Implements;

interface HydratableRepository
{
    public function hydrate(array $data);
}
