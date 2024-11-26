<?php

namespace App\Http\Requests\Api\v1\user;

use App\Models\Traits\Requests\WithPlainErrors;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    use WithPlainErrors;
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
            'name' => ['sometimes', 'filled', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'filled',
                'email',
                Rule::unique('users', 'email')->ignore(Auth::id()),
            ],
            'username' => [
                'sometimes',
                'filled',
                'string',
                Rule::unique('users', 'username')->ignore(Auth::id()),
            ],
//            'birthday' => ['sometimes', 'filled', 'date'],
        ];
    }
}
