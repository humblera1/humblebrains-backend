<?php

namespace App\Services\Api;

use App\Models\CheckpointStage;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class CheckpointService
{
    /**
     * Saves checkpoint stage result for the current user.
     *
     * @param array $stageResult
     * @return void
     *
     * @throws HttpException if no uncompleted stage is found for the provided category
     */
    public function saveStage(array $stageResult): void
    {
        list('category' => $category, 'score' => $score) = $stageResult;

        /** @var User $user */
        $user = Auth::user()->loadLatestCheckpointRelations();

        $checkpoint = $user->latestUncompletedCheckpoint;

        if (is_null($checkpoint)) {
            throw new ModelNotFoundException('No unfinished checkpoint found.');
        }

        $stages = $checkpoint->stages;

        /** @var CheckpointStage $stage */
        $stage = $checkpoint
            ->stages
            ->where('is_completed', false)
            ->where('category.name', $category)
            ->first();

        if (is_null($stage)) {
            throw new ModelNotFoundException('No unfinished stage found by provided category');
        }

        $stage->score = $score;
        $stage->is_completed = true;

        // checking if all stages are completed
        $allStagesCompleted = $stages->every(function ($stage) {
            return $stage->is_completed === true;
        });

        if ($allStagesCompleted) {
            // if the score for the stages of several categories is the same, the user must select the category himself
            $scores = $stages->pluck('score');
            $duplicates = $scores->duplicates();

             if ($duplicates->isEmpty()) {
                 // finally, if all stages are completed, and we can determine desired category
                 // we update the checkpoint and generate new program
                 $checkpoint->is_completed = true;

                 // todo: generate new program in program service by provided category
             }
        }

        $user->push();
    }
}
