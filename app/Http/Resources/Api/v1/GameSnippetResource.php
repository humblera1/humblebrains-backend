<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class GameSnippetResource extends JsonResource
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
            'image' => Storage::url($this->icon_image),
            'max_level' => $this->max_level,
            'user_level' => $this->whenLoaded('userStatistics', function () {
                return $this->userStatistics->first()->max_level ?? 0;
            }, 0),
        ];
    }
}
