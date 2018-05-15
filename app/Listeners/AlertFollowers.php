<?php

namespace App\Listeners;

use App\User;
use App\Events\PostWasCreated;
use App\Notifications\PostWasCreated as createdPost;

class AlertFollowers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

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
                return;
            }
        }

        $event->post->creator->followers()
            ->each
            ->notify(new createdPost($event->post));
    }
}
