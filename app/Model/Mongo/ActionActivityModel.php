<?php

namespace App\Model\Mongo;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class ActionActivityModel extends EloquentModel
{
    protected $connection = 'mongodb';
    protected $collection = 'az_action_activity';
    protected $primaryKey = '_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        
    ];

}
