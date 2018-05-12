<?php

namespace App\Listeners;

use App\User;
use App\Events\PostWasCreated;
use App\Notifications\YouWereMentioned;

class AlertMetionedUsers
{
    /**
     * Handle the event.
     *
     * @param  PostWasCreated  $event
     * @return void
     */
    public function handle(PostWasCreated $event)
    {
        $mentionedUsers = $event->post->mentionedUsers();

        foreach ($mentionedUsers as $slug) {
            $user = User::whereSlug($slug)->first();

            if ($user) {
                $user->notify(new YouWereMentioned($event->post));
            }
        }
    }
}
