<?php

namespace App\Services;

use App\Model\Mongo\OrderActivityModel;
use Carbon\Carbon;
use Exception;

class OrderActivityService
{

    public function __construct(OrderActivityModel $orderActivityModel)
    {
        $this->result = $orderActivityModel;
    }

    public function queryByCustomerId($customerId, $data)
    {
        $query = $this->result;
        if (isset($data['product_id']) && $data['product_id'] != '') {
            $query = $query->where('product_id', $data['product_id']);
        }
        if (isset($data['reason']) && $data['reason'] != '' && $data['reason'] != '-1') {
            $query = $query->where('reason', $data['reason']);
        }
        if (isset($data['updated_at']) && $data['updated_at'] != '') {
            try {
                $start = Carbon::parse($data['updated_at'] . " 00:00:00");
                $end = Carbon::parse($data['updated_at'] . " 23:59:59");
                $query = $query->where('updated_at', '>=' , $start);
                $query = $query->where('updated_at', '<=' , $end);
            } catch (\Throwable $th) {}
        }
        $query = $query->where('user_create_id', $customerId);
        $query = $query->where('status', 1);
        $query = $query->orderBy('updated_at', 'DESC')->groupBy('order_id');
        return $query;
    }

    public function getCondition($query = [])
    {

    }

    public function create($data)
    {
        try {
            $query = new $this->result;
            foreach ($data as $key => $value) {
                $query->$key = $value;
            }
            $query->save();
            return $query;
        } catch (Exception  $e) {
            throw $e;
        }
    }

    public function updateByOrderId($orderId, $data)
    {
        $query = $this->result->where('order_id', $orderId);
        if (empty($query)) {return false;}

        try {
            foreach ($data as $key => $value) {
                $query->update([$key => $value]);
            }
            return $query;
        } catch (Exception  $e) {
            throw $e;
        }
    }

    public function removeByOrderId($data)
    {
        $query = $this->result;
        if(is_array($data['order_id'])){
            $query = $query->whereIn('order_id', $data['order_id']);
        }else{
            $query = $query->where('order_id', $data['order_id']);
        }
        $query = $query->delete();
        return $query;
    }

    public function getLastUpdateLimitNumber($limit = 10)
    {
        $result = $this->result;
        $result = $result->where('status', 1);
        $result = $result->take($limit);
        $result = $result->orderBy('updated_at', 'DESC');
        return $result->get();
    }

    public function getByUserId($userId)
    {
        $result = $this->result;
        if(is_array($userId)){
            $result = $result->whereIn('user_create_id', $userId);
        }else{
            $result = $result->where('user_create_id', $userId);
        }
        $result = $result->orderBy('created_at', 'DESC');
        return $result->get();
    }
}
