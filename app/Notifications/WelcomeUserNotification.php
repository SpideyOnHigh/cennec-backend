<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeUserNotification extends Notification
{
    protected $user;
    protected $otp;

    public function __construct($user, $otp)
    {
        $this->user = $user;
        $this->otp = $otp;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $subject = 'Welcome to '. env('APP_NAME');

        return (new MailMessage())
            ->subject($subject)
            ->greeting('Hello ' . $this->user->first_name . ',')
            ->line('Welcome to our application! We are excited to have you.')
            ->line('Here is your OTP: ' .$this->otp)
            ->line('Thank you for joining us!');
    }
}
