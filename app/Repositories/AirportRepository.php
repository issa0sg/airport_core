<?php

namespace App\Repositories;

use App\Models\AirportName;
use App\Services\DataPersistence\Implements\HydratableRepository;
use Illuminate\Support\Facades\DB;
use App\Services\Airport\AirportRepository as AirportRepositoryInterface;

class AirportRepository implements HydratableRepository, AirportRepositoryInterface
{
    private const int DEFAULT_CHUNK =   100;

    public function hydrate(array $data): void
    {
        foreach ($data as $datum) {
            $item = json_decode('{'.rtrim($datum, ',').'}', true);
            if (!(is_array($item) && $item)) {
                continue;
            }

            $code = key($item);
            $item = $item[$code];

            $airports[] = [
                'code'          =>  $code,
                'area'          =>  $item['area'] ?? null,
                'country'       =>  $item['country'],
                'created_at'    =>  now(),
                'updated_at'    =>  now(),
            ];

            $airportsName[] = [
                'code'      =>  $code,
                'name'      =>  $item['cityName']['ru'],
                'language'  => 'ru',
                'created_at'    =>  now(),
                'updated_at'    =>  now(),
            ];

            $airportsName[] = [
                'code'  =>  $code,
                'name'  =>  $item['cityName']['en'],
                'language' => 'en',
                'created_at'    =>  now(),
                'updated_at'    =>  now(),
            ];
        }

        DB::table('airports')->insert($airports);
        DB::table('airports_name')->insert($airportsName);
    }

    public function getMatches(string $match, ?int $offsetId = null): array
    {
        $query = AirportName::query();

        if ($offsetId) {
            $query->where('id', '>', $offsetId);
        }

        return $query->where('name', 'like', $match.'%')
            ->limit(self::DEFAULT_CHUNK)
            ->get(['id', 'code', 'name', 'language'])
            ->toArray();
    }
}
