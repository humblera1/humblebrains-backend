<?php

namespace App\Services\Api;

use App\Entities\DTOs\checkpoint\StageResultDTO;
use App\Models\Category;
use App\Models\Checkpoint;
use App\Models\CheckpointStage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class CheckpointService
{
    /**
     * Saves checkpoint stage result for the current user.
     *
     * @param StageResultDTO $stageResultDTO
     * @return CheckpointStage
     *
     * @throws ModelNotFoundException if no uncompleted stage is found for the provided category
     */
    public function saveStage(StageResultDTO $stageResultDTO): CheckpointStage
    {
        $categorySubquery = Category::select('id')->where('name', $stageResultDTO->categoryName);

        /** @var CheckpointStage $stage */
        $stage = Auth::user()->latestUncompletedStages()->with('category')->where('category_id', $categorySubquery)->firstOrFail();

        $stage->score = $stageResultDTO->score;
        $stage->is_completed = true;

        $stage->save();

        return $stage;
    }

    public function createCheckpointForCurrentUser(): void
    {
        if ($userId = Auth::id()) {
            $checkpoint = Checkpoint::create(['user_id' => $userId]);
            $categories = Category::all();

            $stages = $categories->map(function ($category) use ($checkpoint) {
                return [
                    'checkpoint_id' => $checkpoint->id,
                    'category_id' => $category->id,
                ];
            })->toArray();

            $stagesTable = (new CheckpointStage())->getTable();

            DB::table($stagesTable)->insert($stages);
        }
    }

    public function finishCheckpoint(): void
    {
        // todo: может быть завершен чекпоинт с незавершенными этапами

        $checkpoint = Auth::user()->latestUncompletedCheckpoint;

        if ($checkpoint) {
            $checkpoint->is_completed = true;

            $checkpoint->save();
        }
    }
}
