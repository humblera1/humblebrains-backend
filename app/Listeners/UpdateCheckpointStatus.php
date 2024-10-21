<?php

namespace App\Listeners;

use App\Events\StageCompleted;

class UpdateCheckpointStatus
{
    /**
     * Handle the event.
     */
    public function handle(StageCompleted $event): void
    {
        $checkpoint = $event->checkpointStage->checkpoint;

        $allStagesCompleted = $checkpoint->stages()->where('is_completed', false)->doesntExist();

        if (!$allStagesCompleted) {
            return;
        }

        if (!$checkpoint->update(['is_completed' => true])) {
            throw new \Exception('Failed to update checkpoint status');
        }

        // ... another event to generate new user session
    }
}
