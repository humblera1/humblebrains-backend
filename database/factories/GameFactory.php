<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class GameFactory extends Factory
{
    protected $model = Game::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'label' => $this->faker->words(),
            'description' => $this->faker->text(),
            'main_image' => $this->faker->word(),
            'thumbnail_image' => $this->faker->word(),
            'icon_image' => $this->faker->word(),
            'max_level' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
