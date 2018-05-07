<?php

Route::view('/', 'home')->middleware('auth');
Route::view('/home', 'home')->middleware('auth')->name('home');
Route::view('/signout', 'signout')->middleware('auth');

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

Route::get('/profiles/{user}', 'ProfilesControllers@show');

Route::get('/posts', 'PostsController@index');
Route::post('/posts', 'PostsController@store');
Route::get('/posts/{post}', 'PostsController@show');
Route::delete('/posts/{post}', 'PostsController@destroy');
Route::post('/posts/{post}/replies', 'RepliesController@store');
Route::get('/replies/{post}', 'RepliesController@index');
Route::delete('/replies/{reply}', 'RepliesController@destroy');
Route::patch('/replies/{reply}', 'RepliesController@update');
Route::patch('/replies/validate/{reply}', 'RepliesController@validated');
Route::post('/recordings', 'RecordingsController@store');
Route::get('/recordings/{recording}', 'RecordingsController@show');

Route::post('/posts/{post}/favorites', 'FavoritesController@store');
Route::delete('/posts/{post}/favorites', 'FavoritesController@destroy');

// API
