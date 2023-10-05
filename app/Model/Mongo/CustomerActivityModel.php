<?php

namespace App\Model\Mongo;
use App\Model\Mongo\UserModel;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class CustomerActivityModel extends EloquentModel
{
    protected $connection = 'mongodb';
    protected $collection = 'az_customer_activity';
    protected $primaryKey = '_id';
    protected $dates = ['created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        
    ];

    public function getDateFormat()
    {
        return 'H:i:s d/m/Y';
    }

    public function customer()
    {
        return $this->belongsTo(UserModel::class, 'user_create_id');
    }


    public function order()
    {
        return $this->belongsTo(OrderListModel::class, 'order_id');
    }

    public function orderCare()
    {
        return $this->belongsTo(OrderCareModel::class, 'order_id');
    }

}
