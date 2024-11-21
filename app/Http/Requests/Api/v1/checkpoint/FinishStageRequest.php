<?php

namespace App\Http\Requests\Api\v1\checkpoint;

use App\Entities\DTOs\checkpoint\StageResultDTO;
use App\Interfaces\Request\RequestDTOInterface;
use App\Models\Traits\Requests\WithPlainErrors;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FinishStageRequest extends FormRequest implements RequestDTOInterface
{
    use withPlainErrors;

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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category' => ['required', 'exists:categories,name'],
            'score' => ['required', 'integer', 'min:0', 'max:100'],
        ];
    }

    public function getDTO(): StageResultDTO
    {
        return new StageResultDTO(
            $this->validated('category'),
            $this->validated('score'),
        );
    }
}
