<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Models\User;
use App\Models\Friend;

class FriendController extends Controller
{
	// Story 1: As a user, I need an API to create a friend connection between two email addresses.
    public function friend()
    {
    	$friends = request()->get('friends');

        // Parameter format validation
        if (is_null($friends) || !is_array($friends) || count($friends) != 2) {
            throw new AppException("FRIEND001", "Parameter Error");
        }

        // User validation
        $requestor = User::where('email', $friends[0])->first();
        $target = User::where('email', $friends[1])->first();

        if (is_null($requestor) || is_null($target)) {
            throw new AppException("FRIEND002", "The user name you specified is incorrect.");
        }


        if ($friends[0] == $friends[1]) {
            throw new AppException("FRIEND003", "You cannot friend yourself.");
        }

        // Friend existence validation. Note that friend relationship is stored in both direction.
        $friend = Friend::where([
            ['requestorID', '=', $requestor->id],
            ['targetID', '=', $target->id],
        ])->first();

        $friendReverse = Friend::where([
            ['requestorID', '=', $target->id],
            ['targetID', '=', $requestor->id],
        ])->first();

        if (!is_null($friend) && !is_null($friendReverse)) {
            throw new AppException('FRIEND004', "The friend relationship between 2 users already exists");
        }

        // Store friend relationship. The relationship is stored in both direction.
        if (is_null($friend)) {
            Friend::addRelationship($requestor, $target);
        }

        if (is_null($friendReverse)) {
            Friend::addRelationship($target, $requestor);
        }

        return response()->json(['success' => true]);
    }

	// Story 2: As a user, I need an API to retrieve the friends list for an email address.
    public function listFriends()
    {
        $email = request()->get('email');

        // Parameter validation
        if (is_null($email)) {
            throw new AppException('LIST001', "Parameter Error.");
        }

        // User validation 
        $userIns = User::where('email', $email)->first();
        if (is_null($userIns)) {
            throw new AppException('LIST002', "The email you specified does not exist.");
        }

        // Get friends list
        $friends = Friend::with('target')->where('requestorID', $userIns->id)->get();

        return response()->json([
            'success' => true,
            'friends' => $friends->pluck('target.email'),
            'count' => $friends->count(),
        ]);
    }

    // Story 3: As a user, I need an API to retrieve the common friends list between two email addresses.
    public function listCommon()
    {

    }

    // Story 5: As a user, I need an API to block updates from an email address.
    public function block()
    {

    }
}
