<?php

namespace App\Entities\DTOs\game;

use App\Entities\DTOs\BaseDTO;

class GameResultDTO extends BaseDTO
{
    public function __construct(
        public readonly string $game,
        public readonly int $score,
        public readonly int $finishedAtLevel,
        public readonly int $maxUnlockedLevel,
        public readonly bool $withinSession,
        public readonly float $meanReactionTime,
        public readonly float $accuracy,
    )
    {}
}
