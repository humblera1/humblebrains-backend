<?php

namespace App\Entities\DTOs\game;

use App\Entities\DTOs\BaseDTO;

class GameResultDTO extends BaseDTO
{
    public function __construct(
        public readonly int $gameId,
        public readonly int $score,
        public readonly int $finishedAtTheLevel,
        public readonly int $maxUnlockedLevel,
        public readonly bool $withinSession,
    )
    {}
}
