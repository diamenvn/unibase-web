<?php

namespace App\Services;

use App\Model\Mongo\OrderShipModel;
use App\Model\Mongo\ActionActivityModel;
use Exception;
use Carbon\Carbon;
class OrderShipService
{

    public function __construct(OrderShipModel $result, ActionActivityModel $action)
    {
        $this->result = $result;
        $this->action = $action;
    }

    public function search($data)
    {
        $query = $this->result;
        if (isset($data['email']) && $data['email'] != '') {
            $query = $query->where('email', $data['email']);
        }
        if (isset($data['phone']) && $data['phone'] != '') {
            $query = $query->where('phone', $data['phone']);
        }
        if (isset($data['name']) && $data['name'] != '') {
            $query = $query->where('name', 'like', '%' . $data['name'] . '%');
        }
        if (isset($data['search']) && $data['search'] != '') {

            $query = $query->where('name', 'like', '%' . $data['search'] . '%');
            $query = $query->orWhere('phone', 'like', '%' . $data['search'] . '%');
        }

        if (isset($data['_id']) && $data['_id'] != '') {
            if(is_array($data['_id'])){
                $query = $query->whereIn('_id', $data['_id']);
            }else{
                $query = $query->where('_id', $data['_id']);
            }
        }
        
        
        if (isset($data['status']) && $data['status'] > -1) {
            if(is_array($data['status'])){
                $data['status'] = array_map(
                    function($value) { return (int)$value; },
                    $data['status']
                );
                $query = $query->whereIn('status', $data['status']);
            }else{
                $query = $query->where('status', $data['status']);
            }
        }
        if (isset($data['user_id']) && $data['user_id'] != '') {
            if(is_array($data['user_id'])){
                $query = $query->whereIn('user_create_id', $data['user_id']);
            }else{
                $query = $query->where('user_create_id', $data['user_id']);
            }
        }
        if (isset($data['user_create_id']) && $data['user_create_id'] != '') {
            if(is_array($data['user_create_id'])){
                $query = $query->whereIn('user_create_id', $data['user_create_id']);
            }else{
                $query = $query->where('user_create_id', $data['user_create_id']);
            }
        }
        
        if (!empty($data['filter_date_by']) && $data['filter_date_by'] == "date_success_order") {
            if (isset($data['time_start']) && $data['time_start'] !== '') {
                try {
                    $dt = Carbon::parse($data['time_start'] . " " . "00:00:00");
                    $query = $query->where($data['filter_date_by'], '>=' , $dt);
                } catch (\Throwable $th) {}
            }
            if (isset($data['time_end']) && $data['time_end'] !== '') {
                try {
                    $dt = Carbon::parse($data['time_end'] . " " . "23:59:59");
                    $query = $query->where($data['filter_date_by'], '<=' , $dt);
                } catch (\Throwable $th) {}
            }
        }

        if (isset($data['sortBy']) && $data['sortBy'] != '') {
            $query = $query->orderBy($data['sortBy'], isset($data['sortOrder']) ? $data['sortOrder'] : 'DESC');
        } else {
            $query = $query->orderBy('_id', 'DESC');
        }

        return $query;
    }

    public function query($data)
    {
        $query = $this->result;
        foreach ($data as $key => $value) {
            if(is_array($value)){
                $query = $query->whereIn($key, $value);
            }else{
                $query = $query->where($key, $value);
            }
        }
        $data = $query;
        return $data;
    }

    public function update($target, $replace)
    {
        try {
            foreach ($replace as $key => $value) {
                $target->$key = $value;
            }
            $target->save();
            return $target;
        } catch (Exception  $e) {
            throw $e;
        }
    }

    public function updateByOrderId($id, $replace)
    {
        $find = $this->result->where('order_id', $id)->first();
        if (!empty($find)){
            $target = $find;
        }else{
            $target = $this->result;
        }
        try {
            foreach ($replace as $key => $value) {
                $target->$key = $value;
            }
            $target->save();
            return $target;
        } catch (Exception  $e) {
            throw $e;
        }
    }

    public function firstById($id)
    {
        return $this->result->find($id);
    }

    public function firstByOrderId($id)
    {
        return $this->result->where('order_id', $id)->first();
    }


    public function getNewDataCustomerLimitNumber($limit = 10)
    {
        $result = $this->result;
        $result = $result->take($limit);
        $result = $result->orderBy('updated_at', 'DESC');
        return $result->get();
    }

    public function getInMonthForMkt($customer, $month)
    {
        $year = date('Y');
        $dateTime = $year . '-' . $month . '-' . 01;
        $startOfMonth = Carbon::parse($dateTime)->startOfMonth();
        $endOfMonth = Carbon::parse($dateTime)->endOfMonth();
        $result = $this->result;
        $result = $result->where('user_create_id', $customer->_id);
        $result = $result->where('created_at', '>=', $startOfMonth);
        $result = $result->where('created_at', '<=', $endOfMonth);
        return $result->get();
    }

    //==== PRODUCT  =====//

    public function getListProductByCompanyId($id)
    {
        $query = $this->product;
        $query = $query->where('company_id', $id);
        return $query->get();
    }

    //==== SOURCE ====//

    public function getListSourceByCompanyId($id)
    {
        $query = $this->source;
        $query = $query->where('company_id', $id);
        return $query->get();
    }

    //==== ACTION ====//

    public function firstAction($id)
    {
        return $this->action->find($id);
    }
}


?>
