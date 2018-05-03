<?php

namespace App\Http\Controllers;

use App\Post;
use App\Recording;
use FFMpeg\Format\Audio\Aac;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecordingsController extends Controller
{
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
    public function store(Request $request)
    {
        $postID = (int)request('postID');

        $this->storeRecording($request, $postID);

        return response([
            'success' => 'Post succesfully created'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Recording  $recording
     * @return \Illuminate\Http\Response
     */
    public function show(Recording $recording)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Recording  $recording
     * @return \Illuminate\Http\Response
     */
    public function edit(Recording $recording)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Recording  $recording
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recording $recording)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Recording  $recording
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recording $recording)
    {
        //
    }

    public function storeRecording($request, $postID)
    {
        // Get the POSTed file
        $file = $request->file('recording');

        
        // Create a random file name
        $fileName = hash('md5', uniqid(mt_rand(), true), false) . '.webm';
        $toDelete = $fileName;
        
        // Move the file to the storage directory
        $file->storeAs('public/recordings', $fileName);

        $fileName = $this->convertToAac($fileName);

        
        // Save our recording
        $recording = Recording::create([
            'post_id'       => $postID,
            'filename'      => $fileName,
            'path'          => '/storage/recordings/' . $fileName,
            'mime_type'     => $file->getClientMimeType(),
            'content_size'  => $file->getClientSize(),
        ]);
    
        // delete webm
        return unlink(storage_path('app/public/recordings/'.$toDelete));
    }

    public function convertToAac($fileName)
    {
        // Initialize FFMpeg
        $ffmpeg = \FFMpeg\FFMpeg::create([
            'ffmpeg.binaries'   => '/usr/local/bin/avconv',
            'ffprobe.binaries'  => '/usr/local/bin/avprobe',
            'timeout'           => 3600,
            'ffmpeg.threads'    => 12,
        ]);


        // Open our OGG file
        $webmAudio = $ffmpeg->open(storage_path() . '/app/public/recordings/' . $fileName);

        
        // Set our AAC format
        $format = new Aac();
        $format->setAudioChannels(2);
        
        // Create a new random file name
        $fileName = hash('md5', uniqid(mt_rand(), true), false) . '.aac';

        $newFilePath = storage_path() . '/app/public/recordings/' . $fileName;

        // Save our file as AAC on the new file path
        $webmAudio->save($format, $newFilePath);

        return $fileName;
    }

}
