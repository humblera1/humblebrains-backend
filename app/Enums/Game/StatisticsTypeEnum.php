<?php

namespace App\Enums\Game;

enum StatisticsTypeEnum: string
{
    case All = 'all';

    case Score = 'score';

    case Accuracy = 'accuracy';
}
