<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSessionProgramResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->id,
            'program' => ProgramResource::make($this->whenLoaded('latestProgram')),
            'checkpoint' => CheckpointResource::make($this->whenLoaded('latestUncompletedCheckpoint')),
        ];
    }
}
