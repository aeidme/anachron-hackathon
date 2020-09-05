<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersModel extends Model
{
    protected $table= 'users_';
    public $primaryKey='user_id';

    protected $fillable = [
        'username', 'token'
    ];
}
