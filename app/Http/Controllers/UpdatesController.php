<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friend;
use App\Models\Subscription;
use App\Exceptions\AppException;

class UpdatesController extends Controller
{
    // Story 5: As a user, I need an API to block updates from an email address.
	public function block()
	{
        $requestor = request()->get('requestor');
        $target = request()->get('target');

        // Parameter validation
        if (is_null($requestor) || is_null($target)) {
            throw new AppException('BLOCK001', 'Parameter Error.');
        }

        // User validation
        $requestorIns = User::findOrThrow($requestor);
        $targetIns = User::findOrThrow($target);

        if ($requestor == $target) {
            throw new AppException('BLOCK002', 'You cannot block yourself.');
        }

        // Relationship check
        $friend = Friend::where([
        	['requestorID', '=', $requestorIns->id],
        	['targetID', '=', $targetIns->id],
        ])->first();
        $subscription = Subscription::where([
        	['requestorID', '=', $requestorIns->id],
        	['targetID', '=', $targetIns->id],
        ])->first();

        if ((is_null($friend) || $friend->isBlocked) && is_null($subscription)) {
        	throw new AppException('BLOCK003', 'You cannot block '.$target.'.');
        }

        // Block operation
        if (!is_null($friend)) {
        	$friend->isBlocked = true;
        	$friend->save();
        }

        if (!is_null($subscription)) {
        	$subscription->delete();
        }
        
        return response()->json(['success' => true]);
	}

	// Story 6: As a user, I need an API to retrieve all email addresses that can receive updates from an email address.
    public function listAll()
    {

    }
}
