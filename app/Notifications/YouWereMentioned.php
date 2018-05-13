<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class YouWereMentioned extends Notification
{
    use Queueable;

    protected $reply;
    protected $post;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($post = null, $reply = null)
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
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if ($this->reply !== null) {
            return (new MailMessage)
                    ->line($this->reply->owner->name . ' mentioned you.')
                    ->action('Go check it out!', url('https://earworm.bordl.net/#/posts/' . $this->reply->post->id))
                    ->line('earworm.bordl.net');
        }
        return (new MailMessage)
                    ->line($this->post->creator->name . ' mentioned you.')
                    ->action('Notification Action', url('https://earworm.bordl.net/#/posts/' . $this->post->id))
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
        if ($this->reply !== null) {
            return [
                'message' => $this->reply->owner->name . ' mentioned you. Go check it out!',
                'postID' => $this->reply->post->id,
                'replyID' => $this->reply->id,
            ];
        }
        return [
            'message' => $this->post->creator->name . ' mentioned you. Go check it out!',
            'postID' => $this->post->id,
        ];
    }
}
