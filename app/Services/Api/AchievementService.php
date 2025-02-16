<?php

namespace App\Services\Api;

use App\Enums\Game\AchievementEnum;
use App\Enums\Game\TotalAchievementEnum;
use App\Models\Game;
use App\Models\History;
use App\Models\Message;
use App\Models\TotalUser;
use App\Models\User;
use App\Models\UserGameStatistic;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class AchievementService
{
    protected const ACHIEVEMENT_MESSAGE_PREFIX = 'achievements';
    protected const TOTAL_ACHIEVEMENT_MESSAGE_PREFIX = 'total-achievements';

    protected array $prefixMap = [
        AchievementEnum::class => self::ACHIEVEMENT_MESSAGE_PREFIX,
        TotalAchievementEnum::class => self::TOTAL_ACHIEVEMENT_MESSAGE_PREFIX,
    ];

    protected const MIN_PERCENT_TO_SHOW_ACHIEVEMENT = 30;

    protected array $achievementsList = [];

    protected User $user;

    protected Game $game;

    protected History|null $latestGame = null;

    protected Collection|null $messages = null;

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

    protected function getLatestGame(): History
    {
        if ($this->latestGame === null) {
            $this->latestGame = $this->user->latestGame;
        }

        return $this->latestGame;
    }

    protected function loadMessages(string $achievementEnumClass): void
    {
        if (!array_key_exists($achievementEnumClass, $this->prefixMap)) {
            throw new \InvalidArgumentException('Unsupported enum type');
        }

        $haystack = $this->prefixMap[$achievementEnumClass] . '%';

        $this->messages = Message::whereLike('key', $haystack)
            ->get()
            ->keyBy('key');

    }

    protected function getMessage(TotalAchievementEnum | AchievementEnum $type): Message|null
    {
        if (is_null($this->messages)) {
            $this->loadMessages($type::class);
        }

        return $this->messages->get($this->getMessageKeyForAchievement($type));
    }

    public function getTotalAchievements(): array
    {
        $this->achievementsList = [];

        $statisticExists = $this->user->gameStatistics()->where('game_id', $this->game->id)->exists();

        if ($statisticExists) {
            $this->checkGamesPlayedTotalAchievement();
            $this->checkOpenedLevelTotalAchievement();
            $this->checkLowerScorePercentageTotalAchievement();
        }

        return $this->achievementsList;
    }

    /**
     * Generates achievements for last game played by user.
     *
     * @return array
     */
    public function getAchievements(): array
    {
        $this->achievementsList = [];

        $historyExists = $this->user->history()->where('game_id', $this->game->id)->exists();

        if ($historyExists) {
            $this->checkLevelAchievements();
            $this->checkLowerScorePercentageAchievement();
            $this->checkNewRecordAchievement();
            $this->checkNoMistakesAchievement();
            $this->checkGamesPlayedAchievement();
            $this->checkTargetCompletedAchievement();
        }

        return $this->achievementsList;
    }

    public function checkLevelAchievements(): void
    {
        // first, check if new level unlocked in last game
        $latestGame = $this->getLatestGame();

        $maxUnlockedLevel = (int) $this->user->history()
            ->where('game_id', $this->game->id)
            ->whereNot('id', $latestGame->id)
            ->max('max_unlocked_level');

        if ($latestGame->max_unlocked_level <= $maxUnlockedLevel) {
            return;
        }

        $this->awardAchievement(AchievementEnum::NewLevelUnlocked);

        // then, if new level unlocked, we check if this level is max level in the game
        if ($latestGame->max_unlocked_level == $this->game->properties()->max('level')) {
            $this->awardAchievement(AchievementEnum::MaxLevelUnlocked);
        }
    }

    public function checkLowerScorePercentageAchievement(): void
    {
        $message = Message::firstWhere('key', $this->getMessageKeyForAchievement(AchievementEnum::LowerScorePercentage));

        if (!$message) {
            return; // No message for this achievement
        }

        $totalUsersCount = TotalUser::value('count');

        if (empty($totalUsersCount)) {
            return; // There is no full-fledged users
        }

        $latestGame = $this->getLatestGame();
        $maxLevel = $this->user->gameStatistics->where('game_id', $this->game->id)->value('max_level');

        $startsFromFinalLevel = $latestGame->started_from_level === $maxLevel;

        $higherScoreUsersQuery = History::select(DB::raw('user_id, AVG(score)'))
            ->where('game_id', $this->game->id)
            ->whereNot('user_id', $this->user->id)
            ->groupBy('user_id')
            ->having(DB::raw('AVG(score)'), '>', $latestGame->score);

        if ($startsFromFinalLevel) {
            $higherScoreUsersQuery->where('started_from_level', $maxLevel);
        } else {
            $higherScoreUsersQuery->where('game_sequence_number', $latestGame->game_sequence_number);
        }

        $higherScoreUsersCount = $higherScoreUsersQuery->count();

        if (empty($higherScoreUsersCount)) {
            $this->awardAchievement(AchievementEnum::LowerScorePercentage, $message->getMessagePluralForm(99));

            return;
        }

        $percent = 100 - round(($higherScoreUsersCount / $totalUsersCount) * 100);
        $roundedPercent = floor($percent / 10) * 10;

        if ($roundedPercent > 100) {
            return; // ???
        }

        if ($roundedPercent < self::MIN_PERCENT_TO_SHOW_ACHIEVEMENT) {
            return;
        }

        $this->awardAchievement(AchievementEnum::LowerScorePercentage, $message->getMessagePluralForm($roundedPercent));
    }

    public function checkNewRecordAchievement(): void
    {
        $latestGame = $this->getLatestGame();

        $oldRecord = (int) $this->user->history()
            ->where('game_id', $this->game->id)
            ->whereNot('id', $latestGame->id)
            ->max('score');

        if ($latestGame->score > $oldRecord) {
            $this->awardAchievement(AchievementEnum::NewRecord);
        }
    }

    public function checkNoMistakesAchievement(): void
    {
        $latestGame = $this->getLatestGame();

        if ((int) $latestGame->accuracy === 100) {
            $this->awardAchievement(AchievementEnum::NoMistakes);
        }
    }

    public function checkGamesPlayedAchievement(): void
    {
        $message = Message::firstWhere('key', $this->getMessageKeyForAchievement(AchievementEnum::GamesPlayed));

        if (!$message) {
            return; // No message for this achievement
        }

        $latestGame = $this->getLatestGame();

        $this->awardAchievement(AchievementEnum::GamesPlayed, $message->getMessagePluralForm($latestGame->game_sequence_number));
    }

    public function checkTargetCompletedAchievement(): void
    {
        $latestGame = $this->getLatestGame();

        if ($latestGame->is_target_completed) {
            $this->awardAchievement(AchievementEnum::TargetCompleted);
        }
    }

    public function checkGamesPlayedTotalAchievement(): void
    {
        $message = Message::firstWhere('key', $this->getMessageKeyForAchievement(TotalAchievementEnum::GamesPlayed));

        if (!$message) {
            return; // No message for this achievement
        }

        $count = $this->user->gameStatistics()->where('game_id', $this->game->id)->value('played_games_amount');

        $this->awardAchievement(TotalAchievementEnum::GamesPlayed, $message->getMessagePluralForm($count));
    }

    public function checkOpenedLevelTotalAchievement(): void
    {
        $message = Message::firstWhere('key', $this->getMessageKeyForAchievement(TotalAchievementEnum::OpenedLevel));

        if (!$message) {
            return; // No message for this achievement
        }

        $level =$this->user->gameStatistics()->where('game_id', $this->game->id)->value('max_level');

        $this->awardAchievement(TotalAchievementEnum::OpenedLevel, $message->getMessagePluralForm($level));
    }

    public function checkLowerScorePercentageTotalAchievement(): void
    {
        $message = Message::firstWhere('key', $this->getMessageKeyForAchievement(TotalAchievementEnum::LowerScorePercentage));

        if (!$message) {
            return; // No message for this achievement
        }

        $totalUsersCount = TotalUser::value('count');

        if (empty($totalUsersCount)) {
            return; // There is no full-fledged users
        }

        $currentUserMaxScore = $this->user->gameStatistics()->value('max_score');

        $higherScoreUsersCount = UserGameStatistic::where('game_id', $this->game->id)
            ->whereNot('user_id', $this->user->id)
            ->where('max_score', '>', $currentUserMaxScore)
            ->count();

        if (empty($higherScoreUsersCount)) {
            $this->awardAchievement(TotalAchievementEnum::LowerScorePercentage, $message->getMessagePluralForm(99));

            return;
        }

        $percent = 100 - round(($higherScoreUsersCount / $totalUsersCount) * 100);
        $roundedPercent = floor($percent / 10) * 10;


        if ($roundedPercent > 100) {
            return; // ???
        }

        if ($roundedPercent < self::MIN_PERCENT_TO_SHOW_ACHIEVEMENT) {
            return;
        }

        $this->awardAchievement(TotalAchievementEnum::LowerScorePercentage, $message->getMessagePluralForm($roundedPercent));
    }

    protected function getMessageKeyForAchievement(TotalAchievementEnum | AchievementEnum $type): string
    {
        return $this->prefixMap[$type::class] . ':' . $type->value;
    }

    public function awardAchievement(TotalAchievementEnum | AchievementEnum $type, string $message = ''): void
    {
        if (empty($message) && !$message = $this->getMessage($type)) {
            return; // No message for this achievement
        }

        $this->achievementsList[] = [
            'type' => $type->value,
            'content' => is_string($message) ? $message : $message->content,
        ];
    }
}
