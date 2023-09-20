<?php

namespace App\Model\Mongo;
use App\Model\Mongo\OrderListModel;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class ListProductModel extends EloquentModel
{
    protected $connection = 'mongodb';
    protected $collection = 'az_product_list';
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

    public function order()
    {
        return $this->belongsTo(OrderListModel::class, 'product_id');
    }

}
