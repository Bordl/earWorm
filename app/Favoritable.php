<?php

namespace App;

trait Favoritable
{
    // Morph relationship
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    // store method
    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];
        if (! $this->favorites()->where($attributes)->exists()) {
            $this->favorites->create($attributes);
        }
    }

    public function isFavorited()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }

    public function getFavoritesCount ()
    {
        return $this->favorites->count();
    }
}