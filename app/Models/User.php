<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\AppException;

class User extends Model
{
    protected $table = "Users";

    public static function findOrThrow($email)
    {
    	$ins = self::where('email', $email)->first();

    	if (is_null($ins)) {
    		throw new AppException('USERMDL001', "The email ".$email." does not exist.");
    	}

    	return $ins;
    }
}
