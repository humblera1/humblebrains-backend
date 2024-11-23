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
            'category' => new CategoryResource($this->whenLoaded('category')),
            $this->mergeWhen($this->whenLoaded('sessions'), [
                'sessions_amount' => $this->sessions->count(),
                'completed_sessions_amount' => $this->sessions->where('is_completed', true)->count(),
                'current_session' => ProgramSessionResource::make($this->getCurrentSession()),
                'is_completed' => $this->isCompleted(),
            ]),
            'createdAt' => $this->created_at->format('Y-m-d'),
        ];
    }

    protected function getCurrentSession(): ProgramSession
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
