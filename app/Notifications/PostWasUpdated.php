<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PostWasUpdated extends Notification
{
    use Queueable;

    protected $post, $reply;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($post, $reply)
    {
        $this->post = $post;
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
        // return ['database'];
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
                    ->line('An earWorm you have subscribed to has been updated.')
                    ->action('Go see it', url('https://earworm.bordl.net/#/posts/' . $this->post->id))
                    ->line('earworm.bordl.net');
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
            'message'   => $this->reply->owner->name . ' replied to an earWorm you have subscribed to. Go check it out!',
            'postID'   => $this->post->id,
        ];
    }
}
