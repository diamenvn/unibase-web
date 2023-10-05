<?php

namespace App\Model\MySql;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserModel extends Authenticatable
{
    // use Authenticatable;

    protected $table = 'zm_user_account';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'uid',
        'username',
        'password',
        'email',
        'mobile',
        'token'
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'password', 'remember_token'
    ];
}
