<?php

namespace App;

use App\Notifications\PostWasUpdated;
use Illuminate\Database\Eloquent\Model;

class PostSubscription extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function notify($reply)
    {
        $this->user->notify(new PostWasUpdated($this->post, $reply));
    }
}
