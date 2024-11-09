<?php

namespace App\Services\Api;

use App\Entities\DTOs\game\GameResultDTO;
use App\Enums\PeriodEnum;
use App\Events\ProgramCompleted;
use App\Models\Game;
use App\Models\History;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class GameService
{
    /**
     * @param GameResultDTO $gameResulDTO
     * @return void
     */
    public function saveGame(GameResultDTO $gameResulDTO): void
    {
        DB::transaction(function () use ($gameResulDTO) {
            /** @var User $user */
            $user = Auth::user();

            $gameId = Game::where('name', $gameResulDTO->game)->value('id');

            $maxSequenceNumber = History::where('user_id', $user->id)
                ->where('game_id', $gameId)
                ->max('game_sequence_number');

            settype($maxSequenceNumber, 'integer');

            $history = new History();

            $history->user_id = $user->id;
            $history->game_id = $gameId;
            $history->score = $gameResulDTO->score;
            $history->finished_at_level = $gameResulDTO->finishedAtTheLevel;
            $history->max_unlocked_level = $gameResulDTO->maxUnlockedLevel;
            $history->mean_reaction_time = $gameResulDTO->meanReactionTime;
            $history->accuracy = $gameResulDTO->accuracy;
            $history->game_sequence_number = $maxSequenceNumber + 1;
            $history->played_at = now();

            $history->save();

            if ($gameResulDTO->withinSession) {
                $user->load('latestProgram.sessions.games.game');

                $program = $user->latestProgram;

                if (!$program) {
                    return; // no uncompleted programs
                }

                // get first uncompleted session of the program
                $session = $program->sessions
                    ->sortBy('id')
                    ->where('is_completed', false)
                    ->first();

                if (!$session) {
                    return; // no uncompleted sessions
                }

                // get first uncompleted game of the session
                $game = $session->games
                    ->sortBy('id')
                    ->where('played_game_id', null)
                    ->first();

                if (!$game) {
                    return; // no uncompleted games
                }

                $game->game_id = $history->game_id; // If user played another game within session
                $game->played_game_id = $history->id;

                // complete the session if all games of this session completed
                if ($session->games->every(fn($game) => $game->played_game_id !== null)) {
                    $session->is_completed = true;
                }

                // create checkpoint if all sessions of this program completed
                if ($program->sessions->every(fn($session) => $session->is_completed)) {
                    event(new ProgramCompleted());
                }

                $program->push();
            }
        });
    }

    public function getGamesList(array $categoryIds = null): Collection
    {
        $gamesQuery = Game::with('tags');

        if ($userId = Auth::id()) {
            $gamesQuery->with(['userStatistics' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }]);
        }

        if ($categoryIds) {
            $gamesQuery->whereHas('category', function ($query) use ($categoryIds) {
                $query->whereIn('id', $categoryIds);
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
