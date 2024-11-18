<?php

namespace App\Services\Api;

use App\Entities\DTOs\game\GameResultDTO;
use App\Entities\DTOs\game\UserGameLevelsDTO;
use App\Entities\DTOs\game\UserGameStatisticsDTO;
use App\Enums\PeriodEnum;
use App\Models\Game;
use App\Models\History;
use App\Models\User;
use App\Models\UserGameStatistic;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class GameService
{
    private const STATISTICS_RECORD_LIMIT = 100;

    private const REDUCTION_FACTOR = 0.25;

    private const GAME_DURATION = 10;

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
            $history->started_from_level = $gameResulDTO->startedFromLevel;
            $history->finished_at_level = $gameResulDTO->finishedAtLevel;
            $history->max_unlocked_level = $gameResulDTO->maxUnlockedLevel;
            $history->mean_reaction_time = $gameResulDTO->meanReactionTime;
            $history->accuracy = $gameResulDTO->accuracy;
            $history->game_sequence_number = $maxSequenceNumber + 1;
            $history->correct_answers_amount = $gameResulDTO->correctAnswersAmount;
            $history->within_session = $gameResulDTO->withinSession;
            $history->is_target_completed = $gameResulDTO->isTargetCompleted;
            $history->played_at = now();

            $history->save();
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

    public function getGameLevelsForCurrentUser(Game $game): UserGameLevelsDTO
    {
        $userStatistics = $game->userStatistics()->where('user_id', \Auth::id())->first();

        $userLevel = $game->lastPlayedGame()->where('user_id', \Auth::id())->value('finished_at_level') ?? 1;
        $maxLevel = $userStatistics->max_level ?? 1;

        $levelsArray = $game->propertiesByLevel()->toArray();

        $target = $this->calculateTarget($userStatistics, $levelsArray, $maxLevel);

        return new UserGameLevelsDTO(
            game: $game->name,
            time: self::GAME_DURATION,
            maxUserLevel: $maxLevel,
            lastUserLevel: $userLevel,
            target: $target,
            levels: $levelsArray,
        );
    }

    public function getAllUserStatisticsForGame(Game $game): UserGameStatisticsDTO
    {
        $statistics = Auth::user()->history()
            ->where('game_id', $game->id)
            ->select(['game_sequence_number', 'score', 'accuracy'])
            ->latest('id')
            ->take(self::STATISTICS_RECORD_LIMIT)
            ->get()
            ->reverse()
            ->toArray();

        if (empty($statistics)) {
            return new UserGameStatisticsDTO();
        }

        return new UserGameStatisticsDTO(
            games: array_column($statistics, 'game_sequence_number'),
            scores: array_column($statistics, 'score'),
            accuracy: array_column($statistics, 'accuracy'),
        );
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

    private function calculateTarget(UserGameStatistic|null $userStatistics, array $levelsArray, int $maxLevel): int
    {
        if ($userStatistics) {
            $answersAmount = $userStatistics->mean_correct_answers_amount;
            $pointsPerAnswer = $levelsArray[$maxLevel]['points_per_answer'];

            $rawTarget = $answersAmount * $pointsPerAnswer;
        } else {
            $firstLevel = $levelsArray[1];

            $rawTarget = $firstLevel['correct_answers_before_finish'] * $firstLevel['successful_rounds_before_promotion'] * $firstLevel['points_per_answer'];
        }

        return $this->adjustTarget($rawTarget);
    }

    /**
     * Logic for adjusting the goal for the upcoming game.
     * In the future, method can be improved
     *
     * @param int $rawTarget
     * @return int
     */
    private function adjustTarget(int $rawTarget): int
    {
        $reducedTarget = $rawTarget * (1 - self::REDUCTION_FACTOR);
        $adjustedTarget = ceil($reducedTarget / 10) * 10;

        return max((int) $adjustedTarget, 10);
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
