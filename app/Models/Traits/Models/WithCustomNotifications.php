<?php

namespace App\Models\Traits\Models;

use App\Notifications\ResetPasswordNotification;

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
}
