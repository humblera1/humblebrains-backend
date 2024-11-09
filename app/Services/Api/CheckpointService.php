<?php

namespace App\Services\Api;

use App\Entities\DTOs\checkpoint\StageResultDTO;
use App\Models\Category;
use App\Models\Checkpoint;
use App\Models\CheckpointStage;
use App\Models\User;
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
        /** @var User $user */
        $user = Auth::user();

        $categorySubquery = Category::select('id')->where('name', $stageResultDTO->categoryName);

        /** @var CheckpointStage $stage */
        $stage = $user->latestUncompletedStages()->where('category.name', $categorySubquery)->firstOrFail();

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
}
