<?php

namespace App\Http\Requests\Api\v1\game;

use App\Entities\DTOs\game\GameResultDTO;
use App\Interfaces\Request\RequestDTOInterface;
use App\Models\Game;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FinishGameRequest extends FormRequest implements RequestDTOInterface
{
    private function getAvailableLevels(string $gameName): array
    {
        return Game::where('name', $gameName)
            ->join('game_level_properties', 'games.id', '=', 'game_level_properties.game_id')
            ->pluck('game_level_properties.level')
            ->toArray();
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $availableLevels = $this->getAvailableLevels($this->post('game'));

        return [
            'game' => ['required', 'string', 'exists:games,name'],
            'score' => ['required', 'integer', 'min:0'],
            'started_from_level' => ['required', 'integer', Rule::in($availableLevels)],
            'finished_at_level' => ['required', 'integer', Rule::in($availableLevels)],
            'max_unlocked_level' => ['required', 'integer', Rule::in($availableLevels)],
            'within_session' => ['required', 'boolean'],
            'mean_reaction_time' => ['required', 'decimal:2'],
            'accuracy' => ['required', 'decimal:1', 'min:0', 'max:100'],
            'correct_answers_amount' => ['required', 'integer', 'min:0'],
            'is_target_completed' => ['required', 'boolean'],
        ];
    }

    public function getDTO(): GameResultDTO
    {
        return new GameResultDTO(
            game: $this->validated('game'),
            score: $this->validated('score'),
            startedFromLevel: $this->validated('started_from_level'),
            finishedAtLevel: $this->validated('finished_at_level'),
            maxUnlockedLevel: $this->validated('max_unlocked_level'),
            withinSession: $this->validated('within_session'),
            meanReactionTime: $this->validated('mean_reaction_time'),
            accuracy: $this->validated('accuracy'),
            correctAnswersAmount: $this->validated('correct_answers_amount'),
            isTargetCompleted: $this->validated('is_target_completed'),
        );
    }
}
