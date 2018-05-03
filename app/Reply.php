<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordActivities;

    protected $guarded = [];

    protected $with =['owner', 'favorites'];
    
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
