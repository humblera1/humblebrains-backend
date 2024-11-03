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
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'tutorial' => GameTutorialResource::make($this->whenLoaded('tutorial')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
        ];
    }
}
