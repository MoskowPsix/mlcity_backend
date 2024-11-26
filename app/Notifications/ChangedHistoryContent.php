<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChangedHistoryContent extends Notification
{
    use Queueable;
    private string $name;
    /**
     * Create a new notification instance.
     */
    public function __construct(string $name)
    {
        $this->name = $name;
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
                    ->subject('Уведомления о изменении меропрятия на которые вы хотите пойти')
                    ->line('Хотим вас проинформировать, что в вашем списке избранных объектов произошли изменения. Мы обновили информацию по некоторым избранным объектам, чтобы обеспечить вам актуальные данные.')
                    ->line('Изменения произошло в : '. $this->name .' <')
                    ->line('Пожалуйста, зайдите в раздел избранных на нашем сайте или в приложении, чтобы ознакомиться с обновлениями и быть в курсе всей актуальной информации.')
                    ->line('Спасибо, что остаетесь с нами!');
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
