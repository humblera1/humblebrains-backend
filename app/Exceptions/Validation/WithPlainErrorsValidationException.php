<?php

namespace App\Exceptions\Validation;

use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class WithPlainErrorsValidationException extends ValidationException
{
    /**
     * Get only one (first) validation error message for each attribute
     * @return array
     */
    public function errors(): array
    {
        $messagesToReturn = [];

        foreach ($this->validator->errors()->messages() as $property => $messages) {
            $messagesToReturn[$property] = Arr::first($messages);
        }

        return $messagesToReturn;
    }
}
