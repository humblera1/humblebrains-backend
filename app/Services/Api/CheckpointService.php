<?php

namespace App\Services\Api;

use App\Entities\DTOs\checkpoint\StageResultDTO;
use App\Models\Category;
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
}
