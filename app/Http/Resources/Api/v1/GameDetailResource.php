<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'description' => $this->description,
            'image' => $this->main_image,
            'max_level' => $this->max_level,
            'user_level' => $this->whenLoaded('userStatistics', function () {
                return $this->userStatistics->first()->max_level ?? 0;
            }, 0),
            'category' => CategoryResource::make($this->category),
            'tutorial' => GameTutorialResource::make($this->tutorial),
            'tags' => TagResource::collection($this->tags),
        ];
    }
}
