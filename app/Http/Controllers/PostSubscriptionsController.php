<?php

namespace App\Http\Controllers;

use App\Post;
use App\PostSubscription;
use Illuminate\Http\Request;

class PostSubscriptionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Post $post)
    {
        $post->subscribe(); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PostSubscription  $postSubscription
     * @return \Illuminate\Http\Response
     */
    public function show(PostSubscription $postSubscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PostSubscription  $postSubscription
     * @return \Illuminate\Http\Response
     */
    public function edit(PostSubscription $postSubscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PostSubscription  $postSubscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PostSubscription $postSubscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PostSubscription  $postSubscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->unsubscribe();
    }
}
