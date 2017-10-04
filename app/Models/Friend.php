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
        return $this->hasOne('App\Models\User', 'requestorID', 'id');
    }

    public function target() 
    {
        return $this->hasOne('App\Models\User', 'targetID', 'id');
    }

    public static function addRelationship(User $requestor, User $target)
    {
        $instance  = new Friend();
        $instance->requestorID = $requestor->id;
        $instance->targetID = $target->id;
        $instance->save();

        return true;
    }
}
