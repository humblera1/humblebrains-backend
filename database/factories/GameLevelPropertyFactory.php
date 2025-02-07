<?php

namespace Database\Factories;

use App\Models\GameLevelProperty;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameLevelPropertyFactory extends Factory
{
    protected $model = GameLevelProperty::class;

    public function definition(): array
    {
        return [
            'value' => $this->faker->boolean,
            'level' => $this->faker->unique()->numberBetween(1, 10),
        ];
    }
}
