<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Friend extends Model
{
    use SoftDeletes;

    protected $table = "Friends";

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
        $instance  = new Friend();
        $instance->requestorID = $requestor->id;
        $instance->targetID = $target->id;
        $instance->isBlocked = false;
        $instance->save();

        return true;
    }
}
