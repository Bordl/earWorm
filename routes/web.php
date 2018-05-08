<?php

Route::view('/', 'home')->middleware('auth');
Route::view('/home', 'home')->middleware('auth')->name('home');
Route::view('/signout', 'signout')->middleware('auth');

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

Route::get('/profiles/{user}', 'ProfilesControllers@show')->name('profile');

Route::get('/profiles/{user}/notifications', 'UserNotificationsController@index');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy');

Route::get('/posts', 'PostsController@index');
Route::post('/posts/{user}', 'PostsController@store');
Route::get('/posts/{post}', 'PostsController@show');
Route::delete('/posts/{post}', 'PostsController@destroy');

Route::post('/posts/{post}/replies', 'RepliesController@store');
Route::get('/replies/{post}', 'RepliesController@index');
Route::delete('/replies/{reply}', 'RepliesController@destroy');
Route::patch('/replies/{reply}', 'RepliesController@update');
Route::patch('/replies/validate/{reply}', 'RepliesController@validated');

Route::get('/users/{user}', 'FollowUsersController@index');
Route::post('/users/{user}/follow', 'FollowUsersController@store');
Route::delete('/user/{user}/follow', 'FollowUsersController@destroy');

Route::post('/posts/{post}/subscriptions', 'PostSubscriptionsController@store');
Route::delete('/posts/{post}/subscriptions', 'PostSubscriptionsController@destroy');

Route::post('/recordings', 'RecordingsController@store');
Route::get('/recordings/{recording}', 'RecordingsController@show');

Route::post('/posts/{post}/favorites', 'FavoritesController@store');
Route::delete('/posts/{post}/favorites', 'FavoritesController@destroy');

// API
