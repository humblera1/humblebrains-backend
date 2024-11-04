<?php

namespace App\Services\Api;

use App\Entities\game\GameResultDTO;
use App\Models\Game;
use App\Models\GamesHistory;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

final class GameService
{
    /**
     * @param GameResultDTO $gameResulDTO
     * @return void
     */
    public function saveGame(GameResultDTO $gameResulDTO): void
    {
        $user = Auth::user();

        // сохранение в games_history и получение идентификатора
        $gameHistory = GamesHistory::create([
            'user_id' => $user->id,
            'game_id' => $gameResulDTO->gameId,
            'level' => $gameResulDTO->finishedAtTheLevel,
            'score' => $gameResulDTO->score,
        ]);

        $gameHistoryId = $gameHistory->id;

        // update max level
        if (true) {

        }

        if ($gameResulDTO->withinSession) {
            // загружаем связи: program.session.game
//            $user->load();

            // сохранение в session_games

        }

    }

    public function getGamesList(int $categoryId = null): Collection
    {
        $user = Auth::user();

        $gamesQuery = Game::with('tags');

        if ($user) {
            $gamesQuery->with(['userStatistics' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }]);
        }

        if ($categoryId) {
            $gamesQuery->whereHas('category', function ($query) use ($categoryId) {
                $query->where('id', $categoryId);
            });
        }

        return $gamesQuery->get();
    }

    public function generateAchievementsForLastGame()
    {

    }
}
