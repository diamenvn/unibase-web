<?php

namespace App\Model\MySql;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CommentModel extends Authenticatable
{
    // use Authenticatable;

    protected $table = 'comment';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'uid',
        'pid',
        'fid',
        'data',
        'time'
    ];

    protected $dates = ['deleted_at'];
}
