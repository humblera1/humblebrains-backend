<?php

namespace App\Listeners;

use App\Events\ProgramCompleted;
use App\Services\Api\CheckpointService;

class CreateCheckpointAfterProgramCompletes
{
    /**
     * Handle the event.
     */
    public function handle(ProgramCompleted $event): void
    {
        app(CheckpointService::class)->createCheckpointForCurrentUser();

        \Auth::user()->loadLatestCheckpointRelations();
    }
}
