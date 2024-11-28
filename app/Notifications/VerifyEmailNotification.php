<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends VerifyEmail
{
    use Queueable;

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Email Verification')
            ->view('emails.verify-mail', ['url' => $this->getVerificationUrl($notifiable)]);
    }

    /**
     * @param $notifiable
     * @return string
     */
    protected function getVerificationUrl($notifiable): string
    {
        $frontendUrl = config('app.frontend_url');

        $signedUrl = URL::temporarySignedRoute(
            'api.v1.verification.verify',
            Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification())
            ]
        );

        return $frontendUrl . '?url=' . urlencode($signedUrl);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
