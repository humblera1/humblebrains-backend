<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'is_anonymous' => $this->is_anonymous,
            'personal_data' => $this->when(!$this->is_anonymous, fn () => $this->getPersonalData()),
            'checkpoint' => new CheckpointResource($this->whenLoaded('latestCheckpoint')),
            'program' => new ProgramResource($this->whenLoaded('latestProgram')),
        ];
    }

    public function getPersonalData(): array
    {
        return [
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'is_email_verified' => (bool) $this->email_verified_at,
            'avatar' => $this->when($this->avatar, fn () => url('uploads/' . $this->avatar)),
        ];
    }
}
