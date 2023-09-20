<?php

namespace App\Model\MySql;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PageModel extends Authenticatable
{
    // use Authenticatable;

    protected $table = 'page';
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
        'name',
        'token',
        'status'
    ];

    protected $dates = ['deleted_at'];
}
