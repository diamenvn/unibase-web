<?php

namespace App\Model\Mongo;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class SettingApiImportModel extends EloquentModel
{
    protected $connection = 'mongodb';
    protected $collection = 'az_setting_api_import';
    protected $primaryKey = '_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        
    ];


    public function customer()
    {
        return $this->belongsTo(CustomerModel::class, 'user_create_id');
    }

}
