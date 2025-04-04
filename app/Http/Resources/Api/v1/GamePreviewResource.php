<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class GamePreviewResource extends JsonResource
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
            'name' => $this->name,
            'label' => $this->label,
            'description' => $this->description,
            'image' => url($this->main_image),
            'max_level' => $this->max_level,
            'user_level' => $this->userStatistics()->where('user_id', Auth::id())->first()->max_level ?? 0,
            'tags' => TagResource::collection($this->whenLoaded('tags')),
        ];
    }
}
