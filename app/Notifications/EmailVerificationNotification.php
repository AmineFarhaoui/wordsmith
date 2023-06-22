<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Arr;

class EmailVerificationNotification extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)->view(
            'emails.email_verification',
            ['url' => $this->verificationUrl($notifiable)],
        );
    }

    /**
     * Get the verification URL for the given notifiable.
     */
    public function verificationUrl($notifiable): string
    {
        $parameters = [
            'id' => $notifiable->getKey(),
            'verification_token' => sha1(
                $notifiable->getEmailForVerification(),
            ),
        ];

        return sprintf(
            '%s/%s?%s',
            config('app.web_url'),
            'auth/verify',
            Arr::query($parameters),
        );
    }
}
