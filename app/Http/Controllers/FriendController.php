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
        $requestor = User::findOrThrow($friends[0]);
        $target = User::findOrThrow($friends[1]);

        if ($friends[0] == $friends[1]) {
            throw new AppException("FRIEND002", "You cannot friend yourself.");
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
            throw new AppException('FRIEND003', "The friend relationship between 2 users already exists");
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
        $userIns = User::findOrThrow($email);

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
        $friends = request()->get('friends');

        // Parameter validation
        if (is_null($friends) || !is_array($friends) || count($friends) != 2) {
            throw new AppException('COMMON001', 'Parameter Error');
        }

        // User validation
        $userA = User::findOrThrow($friends[0]);
        $userB = User::findOrThrow($friends[1]);

        if ($friends[0] == $friends[1]) {
            throw new AppException('COMMON002', 'You specified the same user.');
        }

        $commonFriends = Friend::with('target')
                            ->join(DB::raw("friends AS b"), 'friends.targetID', '=', 'b.targetID')
                            ->where([
                                ['friends.requestorID', '=', $userA->id],
                                ['b.requestorID', '=', $userB->id],
                            ])->get();

        return response()->json([
            'success' => true,
            'friends' => $commonFriends->pluck('target.email'),
            'count' => $commonFriends->count(),
        ]);
    }
}
