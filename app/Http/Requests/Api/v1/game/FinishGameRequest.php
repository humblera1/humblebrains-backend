<?php

namespace App\Http\Requests\Api\v1\game;

use App\Entities\DTOs\game\GameResultDTO;
use App\Interfaces\Request\RequestDTOInterface;
use App\Models\Game;
use Illuminate\Foundation\Http\FormRequest;

class FinishGameRequest extends FormRequest implements RequestDTOInterface
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // todo: продвинутая валидация уровней
        return [
            'gameId' => ['required', 'integer', 'exists:games,id'],
            'score' => ['required', 'integer', 'min:0', 'max:100'],
            'finishedAtTheLevel' => ['required', 'integer'],
            'maxUnlockedLevel' => ['required', 'integer'],
            'withinSession' => ['required', 'boolean'],
        ];
    }

    public function getDTO(): GameResultDTO
    {
        return new GameResultDTO(
            $this->validated('gameId'),
            $this->validated('score'),
            $this->validated('finishedAtTheLevel'),
            $this->validated('maxUnlockedLevel'),
            $this->validated('withinSession'),
        );
    }
}
