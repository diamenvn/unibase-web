<?php

namespace App\Services;

use App\Model\Mongo\ListDistrictModel;
use App\Model\Mongo\ListProvinModel;
use App\Model\Mongo\ListTownModel;
use Exception;

class ListProvinService
{

    public function __construct(ListProvinModel $provinModel, ListDistrictModel $districtModel, ListTownModel $townModel)
    {
        $this->provin = $provinModel;
        $this->district = $districtModel;
        $this->town = $townModel;
    }


    public function create($data)
    {
        try {
            $query = new $this->town;
            foreach ($data as $key => $value) {
                $query->$key = $value;
            }
            $query->save();
            return $query;
        } catch (Exception  $e) {
            throw $e;
        }
    }

    public function getLastUpdateLimitNumber($limit = 10)
    {
        $result = $this->result;
        $result = $result->where('status', 1);
        $result = $result->take($limit);
        $result = $result->orderBy('updated_at', 'DESC');
        return $result->get();
    }

    public function get()
    {
        $result = $this->provin->select('id', 'name', 'code')->get();
        return $result;
    }

    public function getDistrictByProvinId($data)
    {
        $query = $this->district->select('id', 'name', 'code');
        foreach ($data as $key => $value) {
            if(is_array($value)){
                $query = $query->whereIn($key, $value);
            }else{
                $query = $query->where($key, $value);
            }
        }
        $data = $query->get();
        return $data;
    }


    public function getTownByDistrictId($data)
    {
        $query = $this->town;
        foreach ($data as $key => $value) {
            if(is_array($value)){
                $query = $query->whereIn($key, $value);
            }else{
                $query = $query->where($key, $value);
            }
        }
        $data = $query->get();
        return $data;
    }

    public function insertTown($data)
    {
        $query = new $this->town;
        $query = $query->insert($data);
        return $query;
    }

    public function update($target, $data)
    {
        foreach ($data as $key => $value) {
            $target->$key = $value;
        }
        $query = $target->save();
        return $query;
    }

    public function removeDistrict()
    {
        $query = $this->district;
        $query = $query->whereNotNull('id');
        $query = $query->delete();
        return $query;
    }
}
