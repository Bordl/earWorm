<?php

namespace App;

use App\User;
use App\Activity;
use ReflectionClass;
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

				$user->notifications->filter(function($notification) use ($post) {
						return $notification->data['postID'] == $post->id;
					})
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

		$this->subscriptions->filter(function($sub) use ($reply) {
			return $sub->user_id != $reply->user_id;
		})
			->each
			->notify($reply);

		return $reply;
	}

	public function scopeFilter($query, $filters)
	{
		return $filters->apply($query);
	}

	public function subscribe($userID = null)
	{
		$this->subscriptions()->create([
			'user_id'		=> $userID ?: auth()->id(),
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
