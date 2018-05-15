<?php

namespace App;

use App\Events\PostWasCreated;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $with = ['profile'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'slug',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class)->latest();
    }

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    public function slug()
    {
        return $this->slug;
    }

    public function createPost($post)
    {
        $post = $this->posts()->create($post);

        event(new PostWasCreated($post));

        return $post;
    }

    // public function notifyFollowers($post)
    // {
    //     $this->userFollower
    //         ->where('follower_id', '!=', $post->creator()->id)
    //         ->each
    //         ->notify($post);
    // }

    public function follow($userID = null)
    {
        $this->userFollowers()->create([
            'follower_id' => auth()->id(),
        ]);

        return $this;
    }

    public function unfollow($userID = null)
    {
        $this->userFollowers()
            ->where('user_id', $this->id)
            ->where('follower_id', auth()->id())
            ->delete();
    }

    public function userFollowers()
    {
        return $this->belongsTo(FollowSubscription::class, 'user_id');
    }

    public function userFollowings()
    {
        return $this->hasMany(FollowSubscription::class, 'follower_id');
    }

    public function isFollowing($leaderId)
    {
        return $this->userFollowers()
            ->where('user_id', $leaderId)
            ->where('follower_id', auth()->id())
            ->exists();
    }

    public function followers()
    {
        return $this->userFollowers()->where('user_id', $this->id)->get();
    }

    public function following()
    {
        return $this->userFollowings()->where('follower_id', $this->id)->get();
    }
}
