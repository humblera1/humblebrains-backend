<?php

namespace App\Models\Traits\Controllers;

use App\Exceptions\Validation\WithPlainErrorsValidationException;
use Illuminate\Validation\ValidationException;

trait withResponseHelpers
{
    /**
     * @param array $validationErrors
     * @return void
     * @throws ValidationException
     */
    public function responseWithValidationErrors(array $validationErrors): void
    {
        throw ValidationException::withMessages($validationErrors);
    }

    /**
     * @param array $validationErrors
     * @return void
     * @throws WithPlainErrorsValidationException
     */
    public function responseWithPlainValidationErrors(array $validationErrors): void
    {
        throw WithPlainErrorsValidationException::withMessages($validationErrors);
    }

    /**
     * @param string $validationError
     * @param string $property
     * @return void
     * @throws WithPlainErrorsValidationException
     */
    public function responseWithPlainValidationError(string $validationError, string $property = 'general'): void
    {
        throw WithPlainErrorsValidationException::withMessage($validationError, $property);
    }
}
