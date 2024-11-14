<?php

use App\Enums\Game\TotalAchievementEnum;

return [
    TotalAchievementEnum::OpenedLevel->value => [
        'ru' => '{1} :count уровень открыт|[2,3,4] :count уровня открыто|[5,*] :count уровней открыто',
        'en' => '{1} :count level opened|[2,*] :count levels opened',
    ],
    TotalAchievementEnum::LowerScorePercentage->value => [
        'ru' => 'Лучше {1} :count% игрока|[2,3,4] :count% игроков|[5,*] :count% игроков',
        'en' => 'Better than {1} :count% of players|[2,*] :count% of players',
    ],
    TotalAchievementEnum::GamesPlayed->value => [
        'ru' => '{1} :count тренировка завершена|[2,3,4] :count тренировки завершены|[5,*] :count тренировок завершено',
        'en' => '{1} :count training completed|[2,*] :count trainings completed',
    ],
];
