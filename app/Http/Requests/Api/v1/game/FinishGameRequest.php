<?php

namespace App\Http\Requests\Api\v1\game;

use App\Entities\DTOs\game\GameResultDTO;
use App\Interfaces\Request\RequestDTOInterface;
use App\Models\Game;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FinishGameRequest extends FormRequest implements RequestDTOInterface
{
    private array $availableLevels = [];

    private function getAvailableLevels(string $gameName)
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
            'finished_at_level' => ['required', 'integer', Rule::in($availableLevels)],
            'max_unlocked_level' => ['required', 'integer', Rule::in($availableLevels)],
            'within_session' => ['required', 'boolean'],
            'mean_reaction_time' => ['required', 'decimal:2'],
            'accuracy' => ['required', 'decimal:1', 'min:0', 'max:100'],
        ];
    }

    public function getDTO(): GameResultDTO
    {
        return new GameResultDTO(
            $this->validated('game'),
            $this->validated('score'),
            $this->validated('finished_at_level'),
            $this->validated('max_unlocked_level'),
            $this->validated('within_session'),
            $this->validated('mean_reaction_time'),
            $this->validated('accuracy'),
        );
    }
}
