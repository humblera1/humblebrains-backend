<?php

namespace App\Entities\DTOs\game;

use App\Entities\DTOs\BaseDTO;

class UserGameStatisticsDTO extends BaseDTO
{
    public function __construct(
        public readonly array $games = [],
        public readonly array $scores = [],
        public readonly array $accuracy = [],
    ) {}
}
