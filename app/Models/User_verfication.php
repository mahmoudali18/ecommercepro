<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;

class User_verfication extends model
{

    public $table= 'users_verificationCodes';

    protected $fillable = ['user_id', 'code','created_at','updated_at'];


}
