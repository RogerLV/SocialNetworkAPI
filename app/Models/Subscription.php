<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use SoftDeletes;

    protected $table = "Subscriptions";

    protected $dates = ['deleted_at'];

    public function requestor() 
    {
        return $this->hasOne('App\Models\User', 'id', 'requestorID');
    }

    public function target() 
    {
        return $this->hasOne('App\Models\User', 'id', 'targetID');
    }

    public static function addRelationship(User $requestor, User $target)
    {
        $instance  = new Subscription();
        $instance->requestorID = $requestor->id;
        $instance->targetID = $target->id;
        $instance->save();

        return true;
    }
}
