<?php

namespace App\Model\Mongo;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class CompanyListModel extends EloquentModel
{
    protected $connection = 'mongodb';
    protected $collection = 'az_company_list';
    protected $primaryKey = '_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        
    ];
}
