<?php

namespace App\Exceptions\Validation;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
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

    /**
     * @param string $message
     * @param string $property
     * @return static
     */
    public static function withMessage(string $message, string $property = 'general'): static
    {
        return new static(tap(ValidatorFacade::make([], []), function ($validator) use ($message, $property) {
            $validator->errors()->add($property, $message);
        }));
    }
}
