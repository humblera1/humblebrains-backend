<?php

namespace App\Enums\Game;

enum TotalAchievementEnum: string
{
    case OpenedLevel = 'opened-level';

    case LowerScorePercentage = 'lower-score-percentage';

    case GamesPlayed = 'games-played';
}
