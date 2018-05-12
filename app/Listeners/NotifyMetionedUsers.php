<?php

namespace App\Listeners;

use App\User;
use App\Events\PostHasNewReply;
use App\Notifications\YouWereMentioned;

class NotifyMetionedUsers
{
    /**
     * Handle the event.
     *
     * @param  PostHasNewReply  $event
     * @return void
     */
    public function handle(PostHasNewReply $event)
    {
        $reply = $event->reply;
        $post = $event->reply->post;

        $mentionedUsers = $reply->mentionedUsers();

        foreach ($mentionedUsers as $slug) {
            $user = User::whereSlug($slug)->first();

            if ($user) {
                $user->notify(new YouWereMentioned(null, $reply));
            }
        }
    }
}
