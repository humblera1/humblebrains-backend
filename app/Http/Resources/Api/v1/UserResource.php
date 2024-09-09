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
            'isAnonymous' => $this->is_anonymous,
            'personal' => $this->when(!$this->is_anonymous, fn () => $this->getPersonalData()),
        ];
    }

    public function getPersonalData(): array
    {
        return [
            'firstName' => $this->first_name,
            'secondName' => $this->second_name,
            'username' => $this->username,
            'email' => $this->email,
            'isEmailVerified' => (bool) $this->email_verified_at,
        ];
    }
}
