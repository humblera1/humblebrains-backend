<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameLevelsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'max_level' => $this->max_level,
            'user_level' => $this->userStatistics->first()->max_level ?? 0,
            'levels' => $this->propertiesByLevel(),
        ];
    }
}
