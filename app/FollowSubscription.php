<?php

namespace App;

use App\Notifications\PostWasCreated;
use Illuminate\Database\Eloquent\Model;

class FollowSubscription extends Model
{
    protected $guarded = [];

    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    public function notify($post)
    {
        $this->follower->notify(new PostWasCreated($post));
    }
}
