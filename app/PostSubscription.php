<?php

namespace App;

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

    public function notify($met, $reply)
    {
        $this->user->notify(new $met($this->post, $reply));
    }

    public function unNotify($postID, $replyID)
    {
        if ($this->user->notifications->count() == 0) {
            return;
        }

        foreach ($this->user->notifications as $notification) {
            if (isset($notification->data['replyID'])) {
                if ($notification->data['postID'] == $postID && $notification->data['replyID'] == $replyID) {
                    $notification->delete();
                }
            }
        }
    }
}
