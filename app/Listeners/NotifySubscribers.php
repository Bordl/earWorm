<?php

namespace App\Listeners;

use App\User;
use App\Events\PostHasNewReply;

class NotifySubscribers
{
    /**
     * Handle the event.
     *
     * @param  PostHasNewReply  $event
     * @return void
     */
    public function handle(PostHasNewReply $event)
    {
        $post = $event->reply->post;

        $mentionedUsers = $event->reply->mentionedUsers();

        foreach ($mentionedUsers as $slug) {
            $user = User::whereSlug($slug)->first();

            if ($user) {
                return;
            }
        }

        $post->subscriptions
            ->where('user_id', '!=', $event->reply->user_id)
            ->each
            ->notify('\App\Notifications\PostWasUpdated', $event->reply);
    }
}
