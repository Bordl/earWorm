<?php

namespace App\Listeners;

use App\Events\PostHasNewReply;

class SubscribeNewUser
{
    /**
     * Handle the event.
     *
     * @param  PostHasNewReply  $event
     * @return void
     */
    public function handle(PostHasNewReply $event)
    {
        if (!$event->reply->post->isSubscribedTo) {
            $event->reply->post->subscribe();
        };
    }
}
