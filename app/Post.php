<?php

namespace App;

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
