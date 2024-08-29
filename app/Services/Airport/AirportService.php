<?php

namespace App\Services\Airport;

use Illuminate\Support\Facades\Cache;

class AirportService
{

    private const int DEFAULT_CHUNK = 10;

    public function __construct(protected AirportRepository $repository)
    {
    }

    public function getMatches(string $match = '', int $offset = 0): array
    {
        $key = sprintf('%s_%d', $match, $offset);
        if ($cachedAirports = Cache::get($key)) {
            return json_decode($cachedAirports, true);
        }

        $lastCachedId = null;
        if ($lastCachedKey = $this->searchLastCachedKey($match, $offset)) {
            $lastCachedId = $this->lastCachedOffsetId($lastCachedKey);
        }

        $airports = array_chunk($this->repository->getMatches($match, $lastCachedId), self::DEFAULT_CHUNK);
        if (!$airports) {
            return [];
        }

        $index = 0;
        array_map(function ($chunkedAirports) use ($match, $offset, &$index) {
            $key = sprintf('%s_%d', $match, $offset+$index);
            $index++;
            Cache::add(key: $key, value: json_encode(array_values($chunkedAirports)), ttl: 1000);
        }, $airports);

        return json_decode(Cache::get($key), true);
    }

    private function lastCachedOffsetId(string $key): int
    {
        $lastCachedAirports = json_decode(Cache::get($key), true);
        $lastCachedAirport  = end($lastCachedAirports);
        return $lastCachedAirport['id'];
    }

    private function searchLastCachedKey(string $match, int $offset): string|null
    {
        $low = 0;
        $high = $offset;
        $lastExistingOffset = -1;

        while ($low <= $high) {
            $mid = intval(($low + $high) / 2);
            $midKey = sprintf('%s_%d', $match, $mid);

            if (Cache::has($midKey)) {
                $lastExistingOffset = $mid;
                $low = $mid + 1;
            } else {
                $high = $mid - 1;
            }
        }

        if ($lastExistingOffset === -1) {
            return null;
        }

        return sprintf('%s_%d', $match, $lastExistingOffset);
    }

}
