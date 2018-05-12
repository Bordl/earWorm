<?php

namespace App;

use App\Events\PostHasNewReply;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Favoritable, RecordActivities;

    protected $guarded = [];

    protected $with = ['recording', 'replies', 'creator'];

    protected $appends = ['isFavorited', 'isSubscribedTo'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {
            $post->replies->each->delete();

            $followers = $post->creator->followers();

            foreach ($followers as $follower) {
                $user = User::find($follower->follower_id);

                $user->notifications
                    ->where('data.postID', $post->id)
                    ->each
                    ->delete();
            }
        });
    }

    public function recording()
    {
        return $this->hasOne(Recording::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class)->latest();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function notifications()
    {
        return $this->morphTo();
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        event(new PostHasNewReply($reply));

        return $reply;
    }

    public function mentionedUsers()
    {
        preg_match_all('/(?<=profiles\/)(.*?)(?=">@)/', $this->description, $matches);

        return $matches[1];
    }

    public function setDescriptionAttribute($description)
    {
        $this->attributes['description'] = preg_replace_callback(
            '/@([\w\-]+)/',
            function ($matches) {
                if ((preg_match('/-/', $matches[1]) !== 0)) {
                    return '<a class="purple" href="#/profiles/' . $matches[1] . '">@' . ucwords(preg_replace('/-/', ' ', $matches[1])) . '</a>';
                }

                return '<a class="purple" href="#/profiles/' . $matches[1] . '">@' . $matches[1] . '</a>';
            },
            $description
        );
    }

    public function announceValidation($reply)
    {
        $this->subscriptions
            ->where('user_id', '!=', auth()->id())
            ->each->notify('\App\Notifications\PostWasValidated', $reply);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userID = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userID ?: auth()->id(),
        ]);

        return $this;
    }

    public function canUnsubscribe($post)
    {
        $can = $post->replies()
            ->where('user_id', auth()->id())
            ->where('post_id', $post->id)
            ->count();

        return $can == 1 ? true : false;
    }

    public function unsubscribe($userID = null)
    {
        $this->subscriptions()
            ->where('user_id', $userID ?: auth()->id())
            ->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(PostSubscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }
}
