<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StaffUserNotification extends Notification
{
    use Queueable;

    protected $userName;
    protected $resetUrl;

    public function __construct($userName, $resetUrl)
    {
        $this->userName = $userName;
        $this->resetUrl = $resetUrl;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(env('STAFF_INVITE_MAIL_SUBJECT'))
            ->greeting('Hello ' . $this->userName)
            ->line('Welcome to ' . env('APP_NAME'). '.')
            ->line('You have been invited to the platform '.env('APP_NAME').'. The below-attached link will take you to the password resetting screen from where you can set your password and use the platform as a staff member.')
            ->action('Set Password', $this->resetUrl)
            ->line('Best Wishes,')
            ->line('Team '.env('APP_NAME').'');
    }
}
