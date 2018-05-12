<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordActivities;

    protected $guarded = [];

    protected $with = ['owner', 'favorites'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $reply->post->increment('replies_count');
        });

        static::deleted(function ($reply) {
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

    public function updateReply($request)
    {
        $validate = $request['validate'];

        $this->update([
            'validate' => $validate,
        ]);

        $this->updateXP($validate);

        $this->post->update([
            'answered' => $request['answered'],
        ]);
    }

    public function updateXP($validate)
    {
        $validate !== 0 ? $update = ((int)$this->owner->profile->xp + 50) : $update = ((int)$this->owner->profile->xp - 50);
        $this->owner->profile->update([
            'xp' => $update,
        ]);
    }

    public function mentionedUsers()
    {
        // preg_match_all('/@([\w\-]+)/', $this->body, $matches);
        preg_match_all('/(?<=profiles\/)(.*?)(?=">@)/', $this->body, $matches);

        return $matches[1];
    }

    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace_callback(
            '/@([\w\-]+)/',
            function ($matches) {
                if ((preg_match('/-/', $matches[1]) !== 0)) {
                    return '<a class="purple" href="#/profiles/' . $matches[1] . '">@' . ucwords(preg_replace('/-/', ' ', $matches[1])) . '</a>';
                }

                return '<a class="purple" href="#/profiles/' . $matches[1] . '">@' . $matches[1] . '</a>';
            },
            $body
        );

        // $this->attributes['body'] = preg_replace('/@([\w\-]+)/', '<a href="#/profiles/$1">$0</a>', $body);
    }

    public function unNotifySubscribers()
    {
        $this->post->subscriptions
            ->where('post_id', $this->post->id)
            ->each
            ->unNotify($this->post->id, $this->id);

        return $this;
    }
}
