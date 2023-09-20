<?php

namespace App\Model\Mongo;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class SettingConnectModel extends EloquentModel
{
    protected $connection = 'mongodb';
    protected $collection = 'az_setting_connect';
    protected $primaryKey = '_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        
    ];

    public function companyMkt()
    {
        return $this->belongsTo(CompanyListModel::class, 'company_mkt_id');
    }

    public function companySale()
    {
        return $this->belongsTo(CompanyListModel::class, 'company_sale_id');
    }
}
