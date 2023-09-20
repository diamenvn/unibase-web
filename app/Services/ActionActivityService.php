<?php

namespace App\Services;

use App\Model\Mongo\ActionActivityModel;
use Illuminate\Support\Facades\DB;
use Exception;

class ActionActivityService
{

    public function __construct(ActionActivityModel $actionactivityModel)
    {
        $this->result = $actionactivityModel;
    }

    public function get($data = [])
    {
        $query = $this->result;
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

    public function first($data = [])
    {
        $query = $this->result;
        foreach ($data as $key => $value) {
            if(is_array($value)){
                $query = $query->whereIn($key, $value);
            }else{
                $query = $query->where($key, $value);
            }
        }
        $data = $query->first();
        return $data;
    }

    public function create($data)
    {
        try {
            $query = $this->result;
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
}
