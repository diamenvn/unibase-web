<?php

namespace App\Model\Mongo;
use App\Model\Mongo\OrderActivityModel;
use App\Model\Mongo\SettingConnectModel;
use App\Model\Mongo\CompanyProductModel;
use App\Model\Mongo\CompanySourceModel;
use App\Model\Mongo\CompanyListModel;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Auth\User as Authenticatable;

class CustomerModel extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $connection = 'mongodb';
    protected $collection = 'az_user_account';
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

    public function activity()
    {
        return $this->hasMany(OrderActivityModel::class, 'user_create_id')->orderBy('_id', 'DESC');
    }

    public function companySale()
    {
        return $this->hasMany(SettingConnectModel::class, 'company_sale_id', 'company_id')->orderBy('_id', 'DESC');
    }
    
    public function companyMkt()
    {
        return $this->hasMany(SettingConnectModel::class, 'company_mkt_id', 'company_id')->orderBy('_id', 'DESC');
    }

    public function customer()
    {
        return $this->hasMany(CustomerModel::class, 'company_id', 'company_id')->orderBy('_id', 'DESC');
    }

    public function product()
    {
        return $this->hasMany(CompanyProductModel::class, 'company_id', 'company_id')->where('status', 1);
    }

    public function source()
    {
        return $this->hasMany(CompanySourceModel::class, 'company_id', 'company_id');
    }

    public function company()
    {
        return $this->belongsTo(CompanyListModel::class, 'company_id')->where('status', 1);
    }

    public function orderByMkt()
    {
        return $this->hasMany(OrderListModel::class, 'user_create_id');
    }

    public function orderBySale()
    {
        return $this->hasMany(OrderListModel::class, 'user_reciver_id');
    }

    public function settingOrder()
    {
        return $this->hasOne(SettingOrderModel::class, 'company_id', 'company_id');
    }
}
