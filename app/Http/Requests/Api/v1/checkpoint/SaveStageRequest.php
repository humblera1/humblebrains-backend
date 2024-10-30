<?php

namespace App\Http\Requests\Api\v1\checkpoint;

use App\Models\Traits\Requests\WithPlainErrors;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SaveStageRequest extends FormRequest
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
}
