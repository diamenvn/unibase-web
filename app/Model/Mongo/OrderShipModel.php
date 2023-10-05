<?php

namespace App\Model\Mongo;

use App\Model\Mongo\UserModel;
use App\Model\Mongo\OrderListModel;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class OrderShipModel extends EloquentModel
{
    protected $connection = 'mongodb';
    protected $collection = 'az_order_ship';
    protected $primaryKey = '_id';
    protected $dateFormat = 'd-m-Y H:i:s';
    protected $dates = ['date_success_order', 'created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    public function getDateFormat()
    {
        return 'H:i:s d-m-Y';
    }

    public function customer()
    {
        return $this->belongsTo(UserModel::class, 'user_create_id');
    }

    public function activity()
    {
        return $this->belongsTo(ActionActivityModel::class, 'filter_lading_ship');
    }

    public function order()
    {
        return $this->belongsTo(OrderListModel::class, 'order_id');
    }

    public function orderCare()
    {
        return $this->belongsTo(OrderCareModel::class, 'order_id', '_id');
    }
}
