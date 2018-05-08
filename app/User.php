<?php

namespace App;

use App\UserSubscription;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'description',
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
        return 'name';
    }

    public function posts()
    {
        return $this->hasMany(Post::class)->latest();
    }

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    public function createPost($post){
        $post = $this->posts()->create($post);

        
        $this->following->filter(function($fol) use ($post) {
			return $fol->follower_id != $post->follwer_id;
		})
			->each
			->notify($post);

		return $post;
    }

    public function follow($userID = null)
    {
		$this->following()->create([
			'follower_id'		    => auth()->id(),
        ]);

		return $this;
    }
    
    public function unfollow($userID = null)
	{
		$this->following()
			->where('user_id', $this->id)
			->where('follower_id', auth()->id())
			->delete();
	}

    public function following()
	{
		return $this->hasMany(FollowSubscription::class );
    }
    
    public function isFollowing($leaderId)
	{
		return $this->following()
            ->where('user_id', $leaderId)
            ->where('follower_id', auth()->id())
			->exists();
	}
}
