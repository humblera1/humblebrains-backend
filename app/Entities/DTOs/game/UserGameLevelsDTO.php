<?php

namespace App\Entities\DTOs\game;

use App\Entities\DTOs\BaseDTO;

class UserGameLevelsDTO extends BaseDTO
{
    public function __construct(
        public readonly string $game,
        public readonly int $time,
        public readonly int $maxUserLevel,
        public readonly int $lastUserLevel,
        public readonly int $target,
        public readonly array $levels = [],
    ) {}
}
