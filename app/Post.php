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

	protected $appends = ['isFavorited'];

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
		return $this->replies()->create($reply);
	}

	public function scopeFilter($query, $filters)
	{
		return $filters->apply($query);
	}
}
