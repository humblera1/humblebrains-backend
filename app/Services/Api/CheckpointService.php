<?php

namespace App\Services\Api;

use App\Events\StageCompleted;
use App\Models\Category;
use App\Models\CheckpointStage;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class CheckpointService
{
    /**
     * Saves checkpoint stage result for the current user.
     *
     * @throws HttpException If no uncompleted checkpoint is found for the user or if no
     * uncompleted stage is found for the provided category.
     *
     * @throws Exception If the update fails
     */
    public function saveStage(array $stageResult): void
    {
        list('category' => $category, 'score' => $score) = $stageResult;

        /** @var User $user */
        $user = Auth::user();

        $user->load(['latestCheckpoint' => function ($query) {
            $query->uncompleted();
        }]);

        $checkpoint = $user->latestCheckpoint;
        $categoryId = Category::where('name', $category)->value('id');

        if (!$checkpoint) {
            throw new HttpException(400, 'Uncompleted checkpoint not found for user');
        }

        $checkpointStage = CheckpointStage::where('category_id', $categoryId)
            ->where('checkpoint_id', $checkpoint->id)
            ->uncompleted()
            ->first();

        if (!$checkpointStage) {
            throw new HttpException(400, 'Uncompleted stage not found for provided category');
        }

        DB::beginTransaction();

        try {
            $wasUpdated = $checkpointStage->update([
                'score' => $score,
                'is_completed' => true
            ]);

            if (!$wasUpdated) {
                throw new Exception('Failed to update the stage');
            }

            event(new StageCompleted($checkpointStage));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            // Обработка ошибки
            throw $e;
        }
    }
}
