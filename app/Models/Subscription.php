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
        return $this->hasOne('App\Models\User', 'requestorID', 'id');
    }

    public function target() 
    {
        return $this->hasOne('App\Models\User', 'targetID', 'id');
    }
}
