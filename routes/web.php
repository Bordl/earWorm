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
Route::delete('/replies/{reply}', 'RepliesController@destroy');
Route::post('/recordings', 'RecordingsController@store');
Route::get('/recordings/{recording}', 'RecordingsController@show');

Route::post('/replies/{reply}/favorites', 'FavoritesController@store');