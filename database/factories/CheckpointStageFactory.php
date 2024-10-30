<?php

namespace Database\Factories;

use App\Enums\Game\CategoryEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CheckpointStage>
 */
class CheckpointStageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

        ];
    }

    /**
     * Indicate that the stage is completed.
     */
    public function completed(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_completed' => true,
            ];
        });
    }
}
