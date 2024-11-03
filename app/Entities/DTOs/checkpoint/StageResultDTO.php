<?php

namespace App\Entities\DTOs\checkpoint;

use App\Entities\DTOs\BaseDTO;

class StageResultDTO extends BaseDTO
{
    public function __construct(
        public readonly string $categoryName,
        public readonly int $score,
    )
    {}
}
