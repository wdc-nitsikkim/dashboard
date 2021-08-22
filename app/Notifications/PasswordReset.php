<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use App\Traits\RenderNotification;

class PasswordReset extends Notification
{
    use Queueable;
    use RenderNotification;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($name, $link)
    {
        $this->receiversName = $name;
        $this->resetLink = $link;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->error()
            ->subject('Reset account password')
            ->greeting('Hello ' . $this->receiversName . ',')
            ->line('Click the button below to reset your password')
            ->action('Reset Password', url($this->resetLink))
            ->line('Do not share this link with anyone!')
            ->line('')
            ->line('This email can be safely ignored if you haven\'t requested
                a password reset.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
