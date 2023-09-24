<?php

namespace App\Model\Mongo;
use App\Model\Mongo\ListProductModel;
use App\Model\Mongo\SettingConnectModel;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class ProductModel extends EloquentModel
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
        
    ];

    public function product()
    {
        return $this->belongsTo(ListProductModel::class, 'product_id');
    }

    public function connect()
    {
        return $this->belongsTo(SettingConnectModel::class, 'product_id', 'product_id');
    }

}
