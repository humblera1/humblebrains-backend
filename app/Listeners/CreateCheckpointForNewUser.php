<?php

namespace App\Listeners;

use App\Enums\Game\CategoryEnum;
use App\Events\AnonymousRegistered;
use App\Models\Category;
use App\Models\Checkpoint;
use App\Models\CheckpointStage;
use Illuminate\Support\Facades\DB;

class CreateCheckpointForNewUser
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AnonymousRegistered $event): void
    {
        DB::transaction(function () use ($event) {
            $checkpoint = Checkpoint::create(['user_id' => $event->user->getAuthIdentifier()]);
            $categories = Category::all();

            $stages = $categories->map(function ($category) use ($checkpoint) {
                // todo: logic-category:
                $isCompleted = $category->name === CategoryEnum::Logic->value;

                return [
                    'checkpoint_id' => $checkpoint->id,
                    'category_id' => $category->id,
                    // todo: logic-category:
                    'is_completed' => $isCompleted,
                ];
            })->toArray();

            $stagesTable = (new CheckpointStage())->getTable();

            DB::table($stagesTable)->insert($stages);
        });
    }
}
