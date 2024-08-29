<?php

namespace App\Services\Airport;

interface AirportRepository
{
    public function getMatches(string $match, ?int $offsetId = null): array;
}
