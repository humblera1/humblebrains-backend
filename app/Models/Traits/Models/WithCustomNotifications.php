<?php

namespace App\Models\Traits\Models;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;

trait WithCustomNotifications
{
    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Send an email verification notification to the user.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification);
    }
}
