<?php

namespace App\Model\Mongo;
use App\Model\Mongo\ListSourceModel;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class CompanySourceModel extends EloquentModel
{
    protected $connection = 'mongodb';
    protected $collection = 'az_company_source';
    protected $primaryKey = '_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        
    ];

    public function source()
    {
        return $this->belongsTo(ListSourceModel::class, 'source_id');
    }

}
