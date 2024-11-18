<?php

use App\Enums\Game\AchievementEnum;

return [
    AchievementEnum::NewLevelUnlocked->value => [
        'ru' => 'Открыт новый уровень!',
        'en' => 'New level unlocked!',
    ],
    AchievementEnum::MaxLevelUnlocked->value => [
        'ru' => 'Открыт финальный уровень!',
        'en' => 'Final level unlocked!',
    ],
    AchievementEnum::LowerScorePercentage->value => [
        'ru' => '{1}Вы лучше :count% игрока|[2,3,4]Вы лучше :count% игроков|[5,*]Вы лучше :count% игроков',
        'en' => '{1}You are better than :count% of players|[2,*]You are better than :count% of players',
    ],
    AchievementEnum::NewRecord->value => [
        'ru' => 'Поставлен новый рекорд!',
        'en' => 'New record set!',
    ],
    AchievementEnum::NoMistakes->value => [
        'ru' => 'Ни одной ошибки!',
        'en' => 'No mistakes!',
    ],
    AchievementEnum::GamesPlayed->value => [
        'ru' => '{1} :count тренировка завершена!|[2,3,4] :count тренировки завершены!|[5,*] :count тренировок завершено!',
        'en' => '{1} :count training completed!|[2,*] :count trainings completed!',
    ],
    AchievementEnum::TargetCompleted->value => [
        'ru' => 'Цель выполнена!',
        'en' => 'Target completed!',
    ],
];
