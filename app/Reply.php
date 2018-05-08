<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordActivities;

    protected $guarded = [];

    protected $with =['owner', 'favorites'];

    protected static function boot()
    {
        parent::boot();

        static::created(function($reply){
            $reply->post->increment('replies_count');
        });

        static::deleted(function($reply){
            $reply->post->decrement('replies_count');
        });
    }
    
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function deleteAssociatedNotification()
    {
        $user = $this->post->creator;

        if ($user->notifications->count() == 0) return $this;

        foreach ($user->notifications as $notification) {
            if ($notification->data['postID'] == $this->post->id) {
                $notification->delete();
            }
        }

        return $this;
    }
}
