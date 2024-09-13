<?php

namespace App\Http\Requests\Api\v1;

use App\Models\Traits\Requests\WithPlainErrors;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'email' => ['required', 'unique:users','email:255'],
            'password' => ['required', 'string'],
            'username' => ['nullable', 'unique:users', 'string:255'],
            'first_name' => ['nullable', 'string:255'],
            'second_name' => ['nullable', 'string:255'],
        ];
    }
}
