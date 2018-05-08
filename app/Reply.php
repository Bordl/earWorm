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
        $this->post->creator->notifications->where('created_at', $this->created_at)->first()->delete();

        return $this;
    }
}
