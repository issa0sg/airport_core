<?php

namespace App\Jobs;

use App\Services\DataPersistence\Implements\Hydratable;
use App\Services\DataPersistence\Implements\HydratableRepository;
use App\Services\DataPersistence\Json\JsonHydrateService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DataPersistence implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected HydratableRepository  $repository,
        protected string                $filePath,
        protected string                $fileType
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            if ($this->getPersistenceService()->hydrate($this->repository, $this->filePath)) {
                \Log::info('Finish hydrate file '. $this->filePath);
            }
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());
            throw $e;
        }
    }

    private function getPersistenceService(): Hydratable
    {
        return match (true) {
            $this->fileType === 'json'  =>  new JsonHydrateService(),
            default                     =>  throw new Exception('Undefined file type')
        };
    }
}
