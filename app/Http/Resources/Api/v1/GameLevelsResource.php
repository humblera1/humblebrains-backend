<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameLevelsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'max_level' => $this->userStatistics()->where('user_id', \Auth::id())->value('max_level') ?? 1,
            'user_level' => $this->lastPlayedGame()->where('user_id', \Auth::id())->value('finished_at_level') ?? 1,
            'levels' => $this->propertiesByLevel(),
        ];
    }
}
