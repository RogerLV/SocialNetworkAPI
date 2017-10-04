<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subscription;
use App\Exceptions\AppException;

class SubscriptionController extends Controller
{
	// Story 4: As a user, I need an API to subscribe to updates from an email address.
    public function subscribe()
    {
    	$requestor = request()->get('requestor');
    	$target = request()->get('target');

    	// Parameter validation
    	if (is_null($requestor) || is_null($target)) {
    		throw new AppException('SUBSCRIBE001', 'Parameter Error.');
    	}

    	// User validation
    	$requestorIns = User::findOrThrow($requestor);
    	$targetIns = User::findOrThrow($target);

    	if ($requestor == $target) {
    		throw new AppException('SUBSCRIBE002', 'You cannot subscribe yourself.');
    	}

    	// Relationship existence check
    	$existence = Subscription::where([
    		['requestorID', '=', $requestorIns->id],
    		['targetID', '=', $targetIns->id],
    	])->first();

    	if (!is_null($existence)) {
    		throw new AppException('SUBSCRIBE003', 'You have already subscribe '.$target.".");
    	}

    	// Add subscribe relationship
    	Subscription::addRelationship($requestorIns, $targetIns);

    	return response()->json(['success' => true]);
    }
}
