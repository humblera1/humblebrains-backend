<?php

namespace App\Services\Api;

use App\Enums\Game\TotalAchievementEnum;
use App\Models\Game;
use App\Models\Message;
use App\Models\TotalUser;
use App\Models\User;
use App\Models\UserGameStatistic;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

final class AchievementService
{
    protected const TOTAL_ACHIEVEMENT_MESSAGE_PREFIX = 'total-achievement';

    protected const MIN_PERCENT_TO_SHOW_ACHIEVEMENT = 30;

    protected array $achievementsList = [];

    protected User $user;

    protected Game $game;

    /**
     * @param Game $game
     * @throws AuthenticationException
     */
    public function __construct(Game $game)
    {
        $this->game = $game;

        if (!Auth::check()) {
            throw new AuthenticationException('User is not authenticated.');
        }

        $this->user = Auth::user();
    }

    public function getTotalAchievements(): array
    {
        $statisticExists = $this->user->gameStatistics()->where('game_id', $this->game->id)->exists();

        if (!$statisticExists) {
            return []; // No achievements to generate
        }

        $this->checkGamesPlayedTotalAchievement();
        $this->checkOpenedLevelTotalAchievement();
        $this->checkLowerScorePercentageTotalAchievement();

        return $this->achievementsList;
    }

    public function checkGamesPlayedTotalAchievement(): void
    {
        $message = Message::firstWhere('key', $this->getMessageKeyForTotalAchievement(TotalAchievementEnum::GamesPlayed));

        if (!$message) {
            return; // No message for this achievement
        }

        $count = $this->user->gameStatistics()->where('game_id', $this->game->id)->value('played_games_amount');

        $this->awardAchievement(TotalAchievementEnum::GamesPlayed, $message->getMessagePluralForm($count));
    }

    public function checkOpenedLevelTotalAchievement(): void
    {
        $message = Message::firstWhere('key', $this->getMessageKeyForTotalAchievement(TotalAchievementEnum::OpenedLevel));

        if (!$message) {
            return; // No message for this achievement
        }

        $level =$this->user->gameStatistics()->where('game_id', $this->game->id)->value('max_level');

        $this->awardAchievement(TotalAchievementEnum::OpenedLevel, $message->getMessagePluralForm($level));
    }

    public function checkLowerScorePercentageTotalAchievement(): void
    {
        $message = Message::firstWhere('key', $this->getMessageKeyForTotalAchievement(TotalAchievementEnum::LowerScorePercentage));

        if (!$message) {
            return; // No message for this achievement
        }

        $totalUsersCount = TotalUser::value('count');

        $currentUserScoreRatio = $this->user->gameStatistics()->selectRaw('total_score / played_games_amount as score_ratio')->value('score_ratio');

        $lowerScoreUsersCount = UserGameStatistic::where('game_id', $this->game->id)
            ->where('user_id', '!=', $this->user->id)
            ->whereRaw('total_score / played_games_amount < ?', [$currentUserScoreRatio])
            ->count();

        $percent = ($lowerScoreUsersCount / $totalUsersCount) * 100;

        if ($percent > 100) {
            return; // ???
        }

        if ($percent < self::MIN_PERCENT_TO_SHOW_ACHIEVEMENT) {
            return;
        }

        $this->awardAchievement(TotalAchievementEnum::LowerScorePercentage, $message->getMessagePluralForm($percent));
    }

    protected function getMessageKeyForTotalAchievement(TotalAchievementEnum $type): string
    {
        return  self::TOTAL_ACHIEVEMENT_MESSAGE_PREFIX . ':' . $type->value;
    }

    public function awardAchievement(TotalAchievementEnum $type, string $message): void
    {
        $this->achievementsList[$type->value] = $message;
    }
}
