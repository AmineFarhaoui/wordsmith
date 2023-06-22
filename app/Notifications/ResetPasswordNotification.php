<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends ResetPassword implements ShouldQueue
{
    use Queueable;

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)->view(
            'emails.reset_password',
            ['url' => $this->resetPasswordUrl($notifiable)],
        );
    }

    /**
     * Constructs the url used to reset a password.
     */
    protected function resetPasswordUrl($notifiable): string
    {
        return sprintf(
            '%s/%s?token=%s&email=%s',
            config('app.web_url'),
            'auth/password/reset',
            $this->token,
            $notifiable->getEmailForPasswordReset(),
        );
    }
}
