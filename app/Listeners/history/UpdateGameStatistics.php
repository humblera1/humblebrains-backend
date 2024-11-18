<?php

namespace App\Listeners\history;

use App\Events\HistorySaved;
use App\Models\UserGameStatistic;

class UpdateGameStatistics
{
    /**
     * Handle the event.
     */
    public function handle(HistorySaved $event): void
    {
        $history = $event->history;

        $statistics = UserGameStatistic::firstOrNew([
            'user_id' => $history->user_id,
            'game_id' => $history->game_id,
        ]);

        $statistics->played_games_amount += 1;

        if ($history->score > $statistics->max_score) {
            $statistics->max_score = $history->score;
        }

        if ($history->max_unlocked_level > $statistics->max_level) {
            $statistics->max_level = $history->max_unlocked_level;
        }

        $statistics->mean_correct_answers_amount = intval(ceil(($statistics->mean_correct_answers_amount + $history->correct_answers_amount) / $statistics->played_games_amount));

        $statistics->save();
    }
}
