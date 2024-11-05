<?php

namespace App\Services\Api;

use App\Entities\game\GameResultDTO;
use App\Enums\PeriodEnum;
use App\Models\Game;
use App\Models\GamesHistory;
use Illuminate\Support\Carbon;
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
        $gamesQuery = Game::with('tags');

        if ($userId = Auth::id()) {
            $gamesQuery->with(['userStatistics' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }]);
        }

        if ($categoryId) {
            $gamesQuery->whereHas('category', function ($query) use ($categoryId) {
                $query->where('id', $categoryId);
            });
        }

        return $gamesQuery->get();
    }

    public function getUserStatisticsForGame(Game $game, PeriodEnum $period = PeriodEnum::All): array
    {
        $statisticsQuery = Auth::user()->history()->where('game_id', $game->id);

        if (!$statisticsQuery->exists()) {
            return []; // There is no statistics at all
        }

        if ($dateRange = $this->getDateRangeForPeriod($period)) {
            $statisticsQuery->whereBetween('played_at', $dateRange);
        }

        $statistics = $statisticsQuery->select(['game_sequence_number as number', 'score'])->pluck('score', 'number')->toArray();

        return [
            'xAsis' => array_merge([0], array_keys($statistics)),
            'yAsis' => array_merge([0], $statistics),
        ];

    }

    private function getDateRangeForPeriod(PeriodEnum $period): ?array
    {
        $endDate = Carbon::now();

        switch ($period) {
            case PeriodEnum::Week:
                $startDate = Carbon::now()->subWeek();
                break;
            case PeriodEnum::Month:
                $startDate = Carbon::now()->subMonth();
                break;
            case PeriodEnum::Year:
                $startDate = Carbon::now()->subYear();
                break;
            case PeriodEnum::All:
            default:
                return null;
        }

        return [$startDate, $endDate];
    }
}
