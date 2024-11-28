<?php

namespace App\Http\Requests\Api\v1\user;

use App\Entities\DTOs\user\ChangePasswordDTO;
use App\Interfaces\Request\RequestDTOInterface;
use App\Models\Traits\Requests\WithPlainErrors;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends FormRequest implements RequestDTOInterface
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
            'current_password' => ['required', 'string', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    public function getDTO(): ChangePasswordDTO
    {
        return new ChangePasswordDTO(
            currentPassword: $this->validated('current_password'),
            newPassword: $this->validated('new_password')
        );
    }
}
