<?php

namespace App\Models\Traits\Requests;

use App\Exceptions\Validation\WithPlainErrorsValidationException;
use Illuminate\Contracts\Validation\Validator;

trait WithPlainErrors
{
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw (new WithPlainErrorsValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
