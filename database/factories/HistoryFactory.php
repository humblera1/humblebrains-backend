<?php

namespace Database\Factories;

use App\Models\History;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistoryFactory extends Factory
{
    protected $model = History::class;

    public function definition(): array
    {
        return [
            'score' => 50,
            'started_from_level' => 1,
            'finished_at_level' => 1,
            'max_unlocked_level' => 1,
            'game_sequence_number' => 1,
            'mean_reaction_time' => 1.23,
            'accuracy' => 99,
            'correct_answers_amount' => 9,
            'within_session' => false,
            'is_target_completed' => false,
            'played_at' => now(),
        ];
    }

    /**
     *
     */
    public function unlockedLevel(int $level): self
    {
        return $this->state(function (array $attributes) use ($level) {
            return [
                'max_unlocked_level' => $level,
            ];
        });
    }

    public function sequenceNumber(int $num): self
    {
        return $this->state(function (array $attributes) use ($num) {
            return [
                'game_sequence_number' => $num,
            ];
        });
    }

    public function score(int $score): self
    {
        return $this->state(function (array $attributes) use ($score) {
            return [
                'score' => $score,
            ];
        });
    }

    public function noMistakes(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'accuracy' => 100,
            ];
        });
    }

    public function targetCompleted(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_target_completed' => true,
            ];
        });
    }
}
