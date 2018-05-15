<?php

namespace App\Listeners;

use App\User;
use App\Events\PostWasCreated;
use App\Notifications\PostWasCreated as newPost;

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

        $event->post->creator->userFollowers()
            ->where('follower_id', '!=', $event->post->creator->id)
            ->pluck('follower_id')
            ->each
            ->notify(new newPost($event->post));
    }
}
