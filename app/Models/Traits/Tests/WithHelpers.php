<?php

namespace App\Models\Traits\Tests;

use App\Models\Game;
use App\Models\GameLevelProperty;
use App\Models\Property;

trait WithHelpers
{
    /**
     * Helper method to simulate finishing a game.
     */
    protected function finishGame(array $overrides = []): void
    {
        $data = array_merge([
            'game' => $this->game->name,
            'score' => 1,
            'started_from_level' => 1,
            'finished_at_level' => 1,
            'max_unlocked_level' => 1,
            'within_session' => true,
            'mean_reaction_time' => 1.23,
            'accuracy' => number_format(1.0, 1),
            'correct_answers_amount' => 1,
            'is_target_completed' => false,
        ], $overrides);

        $this->postJson(route('api.v1.games.finish-game'), $data)->assertOk();
    }

    protected function createPropertiesForGame(Game $game, $amount = 10): void
    {
        $property = Property::factory()->create();

        GameLevelProperty::factory()
            ->for($game)
            ->for($property)
            ->createMany($amount);
    }
}
