<?php

namespace App\Http\Controllers;

use App\Http\Requests\Airports\MatchRequest;
use App\Http\Requests\Airports\UploadRequest;
use App\Services\Airport\AirportService;
use App\Services\DataPersistence\DataPersistenceService;
use App\Services\DataPersistence\Implements\HydratableRepository;
use Illuminate\Http\JsonResponse;
class AirportController extends Controller
{
    public function __construct(
        protected AirportService            $airportService,
        protected DataPersistenceService    $persistenceService,
        protected HydratableRepository      $repository
    )
    {
    }

    public function index(MatchRequest $request)
    {
        return response()->json(
            $this->airportService->getMatches(
                match:  $request->get('match', ''),
                offset: $request->get('offset', 0)
            )
        );
    }

    public function upload(UploadRequest $request): JsonResponse
    {
        if ($request->hasFile(DataPersistenceService::UPLOAD_FILE_NAME)) {
            $result = $this->persistenceService->uploadFile($request->file(DataPersistenceService::UPLOAD_FILE_NAME));
            if ($result) {
                $this->persistenceService->prepareHydrate($this->repository);
            }
        }

        return response()->json([
            'uploaded' => $result ?? false
        ]);
    }
}
