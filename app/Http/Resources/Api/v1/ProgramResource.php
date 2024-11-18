<?php

namespace App\Http\Resources\Api\v1;

use App\Models\ProgramSession;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramResource extends JsonResource
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
            'last_session' => $this->whenLoaded('sessions', function () {
                return ProgramSessionResource::make($this->getSession());
            }),
            'is_completed' => $this->whenLoaded('sessions', function () {
                return $this->isCompleted();
            }),
        ];
    }

    protected function getSession(): ProgramSession
    {
        // searching for first uncompleted session
        $session = $this->sessions
            ->sortBy('id')
            ->firstWhere('is_completed', false);

        // if there is no uncompleted session returns latest program session
        if (!$session) {
            $session = $this->sessions
                ->sortBy('id')
                ->last();
        }

        return $session;
    }

    protected function isCompleted(): bool
    {
        return $this->sessions->every(fn($session) => $session->is_completed);
    }
}
