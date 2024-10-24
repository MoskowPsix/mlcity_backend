<?php

namespace App\Notifications;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordRecovery extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Восстановление пароля')
            ->action('Перейдите по ссылке, чтоб востановить пароль', $this->verificationCode($notifiable))
            ->line('Спасибо что используете vokrug.city!');
    }

    private function verificationCode($notifiable): string
    {
        $user = auth('api')->user();
        $code = encrypt(Carbon::now() . ',' . $user->email . ',' . $user->id);
        $url = env('FRONT_APP_URL') . '/recovery/' . $code;
        return $url;
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
