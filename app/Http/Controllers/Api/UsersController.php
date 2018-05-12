<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function index()
    {
        $search = request('name');

        return User::where('id', '!=', auth()->id())
            ->where('name', 'LIKE', "$search%")
            ->take(5)
            ->pluck('slug');
    }
}
