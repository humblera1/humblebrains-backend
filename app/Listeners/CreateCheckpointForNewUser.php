<?php

namespace App\Listeners;

use App\Models\Category;
use App\Models\Checkpoint;
use App\Models\CheckpointStage;
use Illuminate\Auth\Events\Registered;
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
    public function handle(Registered $event): void
    {
        DB::transaction(function () use ($event) {
            $checkpoint = Checkpoint::create(['user_id' => $event->user->getAuthIdentifier()]);
            $categories = Category::all();

            $stages = $categories->map(function ($category) use ($checkpoint) {
                return [
                    'checkpoint_id' => $checkpoint->id,
                    'category_id' => $category->id,
                ];
            })->toArray();

            $stagesTable = (new CheckpointStage())->getTable();

            DB::table($stagesTable)->insert($stages);
        });
    }
}
