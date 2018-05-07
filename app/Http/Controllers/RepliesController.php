<?php

namespace App\Http\Controllers;

use App\Post;
use App\Reply;
use Illuminate\Http\Request;

class RepliesController extends Controller
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
    public function index(Request $request, Post $post)
    {   
        $replies = $post->replies()->get();

        $latest = $replies->sortByDesc('created_at');

        return response([
            'post'      => $post,
            'replies'      => $latest,
        ]);
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
    public function store(Post $post, Request $request)
    {
        $request->validate([
            'body' => 'required_without:link',
            'link' => 'required_without:body',
        ]);

        $reply = $post->addReply([
            'user_id'   => auth()->id(),
            'post_id'   => $post->id,
            'body'      => request('body'),
            'link'      => request('link'),
            'validate'  => 0
        ]);

        return $reply->load('owner');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function show(Reply $reply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function edit(Reply $reply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);

        $request->validate([
            'body' => 'required_without:link',
            'link' => 'required_without:body',
            'validate' => 'integer',
        ]);

        $reply->update([
            'body'      => request('body'),
            'link'      => request('link'),
            'validate'      => request('validate'),
        ]);

    }


    public function validated(Request $request, Reply $reply)
    {
        if(auth()->id() !== request('user_id')) return;

        $reply->update([
            'validate'      => request('validate'),
        ]);

        $reply->post->update([
            'answered' => request('answered')
        ]);

        return $reply;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        return response([
            'message'   => 'Reply successfully deleted.'
        ], 200);
    }
}
