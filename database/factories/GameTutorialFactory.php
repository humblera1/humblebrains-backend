<?php

namespace Database\Factories;

use App\Models\GameTutorial;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameTutorialFactory extends Factory
{
    protected $model = GameTutorial::class;

    public function definition(): array
    {
        return [
            'content' => $this->faker->text(),
        ];
    }
}
