<?php

namespace App\Model\Mongo;
use App\Model\Mongo\UserModel;
use App\Model\Mongo\CustomerModel;
use App\Model\Mongo\ListProductModel;
use App\Model\Mongo\ListSourceModel;
use App\Model\Mongo\LabelListModel;
use App\Model\Mongo\CompanyListModel;
use App\Model\Mongo\OrderActivityModel;
use App\Model\Mongo\ActionActivityModel;
use App\Model\Mongo\CompanyProductModel;
use App\Model\Mongo\CompanySourceModel;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class OrderListModel extends EloquentModel
{
    protected $connection = 'mongodb';
    protected $collection = 'az_order_list';
    protected $primaryKey = '_id';
    protected $dates = ['date_confirm','date_reciver', 'created_at', 'updated_at'];
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
        'time',
        'customer_id',
        'user_reciver_id'
    ];
    public function getDateFormat()
    {
        return 'H:i:s d/m/Y';
    }

    public function customer()
    {
        return $this->belongsTo(UserModel::class, 'user_create_id');
    }
    public function customer_1()
    {
        return $this->belongsTo(CustomerModel::class, 'customer_id');
    }
    
    public function reciver()
    {
        return $this->belongsTo(UserModel::class, 'user_reciver_id');
    }

    public function care()
    {
        return $this->belongsTo(UserModel::class, 'user_care_id');
    }

    public function order()
    {
        return $this->hasMany(OrderListModel::class, 'phone', 'phone')->orderBy('_id', 'DESC');
    }

    public function customerCreateOrder()
    {
        return $this->belongsTo(UserModel::class, 'user_create_id');
    }

    public function product()
    {
        return $this->belongsTo(ListProductModel::class, 'product_id');
    }

    public function source()
    {
        return $this->belongsTo(ListSourceModel::class, 'source_id');
    }

    public function ship()
    {
        return $this->hasOne(OrderShipModel::class, 'order_id')->orderBy('_id', 'DESC');
    }

    public function companyProduct()
    {
        return $this->hasMany(CompanyProductModel::class, 'company_id', 'company_id');
    }

    public function companySource()
    {
        return $this->hasMany(CompanySourceModel::class, 'company_id', 'company_id');
    }

    public function companyCustomer()
    {
        return $this->hasMany(UserModel::class, 'company_id', 'company_id')->where('status', 1);
    }

    public function company()
    {
        return $this->belongsTo(CompanyListModel::class, 'company_id');
    }

    public function activity()
    {
        return $this->hasMany(OrderActivityModel::class, 'order_id')->orderBy('_id', 'DESC');
    }

    public function activityCare()
    {
        return $this->hasMany(OrderActivityModel::class, 'order_id', 'order_id')->orderBy('_id', 'DESC');
    }

    public function filterConfirm()
    {
        return $this->belongsTo(ActionActivityModel::class, 'filter_confirm')->orderBy('_id', 'DESC');
    }

    public function filterStatus()
    {
        return $this->belongsTo(ActionActivityModel::class, 'filter_status')->orderBy('_id', 'DESC');
    }

    public function orderCare()
    {
        return $this->hasOne(OrderCareModel::class, 'order_id')->orderBy('_id', 'DESC');
    }

    public function activityLanding()
    {
        return $this->hasMany(OrderActivityModel::class, 'order_id')->orderBy('_id', 'DESC');
    }

    public function step()
    {
        return $this->belongsTo(LabelListModel::class, 'label_id');
    }
}
