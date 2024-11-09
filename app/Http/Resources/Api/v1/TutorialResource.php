<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TutorialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'content' => $this->content,
        ];
    }
}
