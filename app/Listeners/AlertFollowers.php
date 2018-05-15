<?php

namespace App\Listeners;

use App\User;
use App\Events\PostWasCreated;

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

        dd($event->post->creator->userFollowers()->where('follower_id', '!=', $event->post->user_id)->get());

        $event->post->creator->userFollowers()
            ->where('user_id', '!=', $event->post->user_id)
            ->each
            ->notify('\App\Notifications\PostWasCreated', $event->post);
    }
}
