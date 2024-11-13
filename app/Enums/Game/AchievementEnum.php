<?php

namespace App\Enums\Game;

enum AchievementEnum: string
{
    case NewLevelUnlocked = 'new-level-unlocked';

    case MaxLevelUnlocked = 'max-level-unlocked';

    case LowerScorePercentage = 'lower-score-percentage';

    case NewRecord = 'new-record';

    case NoMistakes = 'no-mistakes';

    case GamesPlayed = 'games-played';
}
