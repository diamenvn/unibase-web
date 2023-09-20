<?php

namespace App\Model\Mongo;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class ListSourceModel extends EloquentModel
{
    protected $connection = 'mongodb';
    protected $collection = 'az_source_list';
    protected $primaryKey = '_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        
    ];
}
