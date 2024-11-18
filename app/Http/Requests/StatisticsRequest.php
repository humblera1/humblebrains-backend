<?php

namespace App\Http\Requests;

use App\Enums\Game\StatisticsTypeEnum;
use App\Enums\PeriodEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StatisticsRequest extends FormRequest
{
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
        return [
            'period' => [Rule::enum(PeriodEnum::class)],
            'type' => [Rule::enum(StatisticsTypeEnum::class)],
        ];
    }

    public function getPeriod(): PeriodEnum
    {
        return PeriodEnum::from($this->validated('period', 'all'));
    }

    public function getType(): StatisticsTypeEnum
    {
        return StatisticsTypeEnum::from($this->validated('type', 'all'));
    }
}
