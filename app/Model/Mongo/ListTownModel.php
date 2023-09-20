<?php

namespace App\Model\Mongo;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class ListTownModel extends EloquentModel
{
    protected $connection = 'mongodb';
    protected $collection = 'az_town_list';
    protected $primaryKey = '_id';

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
}
