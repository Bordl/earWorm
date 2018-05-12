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

        $post->followUser
            ->where('user_id', '!=', $event->post->user_id)
            ->each
            ->notify('\App\Notifications\PostWasUpdated', $event->post);
    }
}
