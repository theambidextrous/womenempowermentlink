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
    public function __construct()
    {
        //
    }
    /** Exponent */
    public function via($notifiable)
    {
        return [ExpoChannel::class];
    }

    public function routeNotificationForExpoPushNotifications()
    {
        return 'expo_token';//THIS IS THE EXPO PUSH TOKEN ATTRIBUTE IN YOUR $notifiable
    }
    public function toExpoPush($notifiable)
    {        
        return ExpoMessage::create()
        ->badge(1)
        ->enableSound()
        ->title($notifiable->title)
        ->body($notifiable->message)
        ->setChannelId($notifiable->channel)
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
