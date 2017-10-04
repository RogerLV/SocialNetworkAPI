<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Debugging page by sending and receving posted data.
Route::get('test', function () {
	return view('test');
});


// Story 1: As a user, I need an API to create a friend connection between two email addresses.
Route::get('friend', 'FriendController@friend')->name('Friend');

// Story 2: As a user, I need an API to retrieve the friends list for an email address.
Route::get('list\friends', 'FriendController@listFriends')->name('ListFriends');

// Story 3: As a user, I need an API to retrieve the common friends list between two email addresses.
Route::get('list\common', 'FriendController@listCommon')->name('ListCommon');


// Story 4: As a user, I need an API to subscribe to updates from an email address.
Route::get('subscribe', 'SubscriptionController@subscribe')->name('Subscribe');


// Story 5: As a user, I need an API to block updates from an email address.
Route::get('block', 'UpdatesController@block')->name('Block');

// Story 6: As a user, I need an API to retrieve all email addresses that can receive updates from an email address.
Route::get('list\updates', 'UpdatesController@listAll')->name('ListUpdates');