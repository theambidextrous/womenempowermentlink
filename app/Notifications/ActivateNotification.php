<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\ExpoPushNotifications\ExpoChannel;
use NotificationChannels\ExpoPushNotifications\ExpoMessage;

class ActivateNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $payload;

    public function __construct($payload)
    {
        $this->payload = $payload;
    }
    /** Exponent */
    public function via($notifiable)
    {
        return [ExpoChannel::class];
    }

    public function routeNotificationForExpoPushNotifications()
    {
        return 'device_token';//THIS IS THE EXPO PUSH TOKEN ATTRIBUTE IN YOUR $notifiable
    }
    public function toExpoPush($notifiable)
    {        
        return ExpoMessage::create()
        ->badge(1)
        ->enableSound()
        ->title($this->payload->title)
        ->body($this->payload->message)
        ->setChannelId($this->payload->channel)
        ->ttl(60)
        ->priority('high');
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
