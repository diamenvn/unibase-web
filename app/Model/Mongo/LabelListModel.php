<?php

namespace App\Model\Mongo;
use App\Model\Mongo\OrderListModel;

use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class LabelListModel extends EloquentModel
{
    protected $connection = 'mongodb';
    protected $collection = 'az_order_labels';
    protected $primaryKey = '_id';
    protected $dateFormat = 'd-m-Y H:i:s';
    protected $dates = ['created_at', 'updated_at'];

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

    public function order()
    {
        return $this->hasMany(OrderListModel::class, 'label_id', '_id')->orderBy('_id', 'DESC');
    }
}
