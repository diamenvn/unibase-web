<?php

namespace App\Services;

use App\Model\Mongo\CompanyProductModel;
use App\Model\Mongo\CompanySourceModel;
use App\Model\Mongo\ListProductModel;
use App\Model\Mongo\OrderListModel;
use Exception;
use Carbon\Carbon;

class OrderListService
{

    public function __construct(OrderListModel $orderListModel, CompanyProductModel $product, CompanySourceModel $source, ListProductModel $listProduct)
    {
        $this->result = $orderListModel;
        $this->listProduct = $listProduct;
        $this->product = $product;
        $this->source = $source;
    }

    public function search($data)
    {
        // dd($data);
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
        
        if (isset($data['source_id']) && $data['source_id'] != '') {
            $query = $query->where('source_id', $data['source_id']);
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
        if (isset($data['user_create_id']) && $data['user_create_id'] != '') {
            
            if(is_array($data['user_create_id'])){
                $query = $query->whereIn('user_create_id', $data['user_create_id']);
            }else{
                $query = $query->where('user_create_id', $data['user_create_id']);
            }
        }
        if (isset($data['product_id']) && $data['product_id'] != '') {
            if(is_array($data['product_id'])){
                $query = $query->whereIn('product_id', $data['product_id']);
            }else{
                $query = $query->where('product_id', $data['product_id']);
            }
        }
        if (isset($data['company_id']) && $data['company_id'] != '') {
            if(is_array($data['company_id'])){
                $query = $query->whereIn('company_id', $data['company_id']);
            }else{
                $query = $query->where('company_id', $data['company_id']);
            }
        }
        if (isset($data['company_mkt_id']) && $data['company_mkt_id'] != '') {
            if(is_array($data['company_mkt_id'])){
                $query = $query->whereIn('company_id', $data['company_mkt_id']);
            }else{
                $query = $query->where('company_id', $data['company_mkt_id']);
            }
        }

        if (isset($data['user_reciver_id'])) {
            if(is_array($data['user_reciver_id'])){
                $query = $query->where(function($query) use ($data){
                    foreach ($data['user_reciver_id'] as $reciver) {
                        if ($reciver == null) {
                            $query = $query->orWhereNull('user_reciver_id');
                        }else{
                            $query = $query->orWhere('user_reciver_id', $reciver);
                        }
                    }
                });
            }else{
                $query = $query->where('user_reciver_id', $data['user_reciver_id']);
            }
        }
    

        if (isset($data['filter_status']) && $data['filter_status'] > -1) {
            if(is_array($data['filter_status'])){
                $data['filter_status'] = array_map(
                    function($value) { return $value; },
                    $data['filter_status']
                );
                $query = $query->whereIn('filter_status', $data['filter_status']);
            }else{
                $query = $query->where('filter_status', $data['filter_status']);
            }  
        }
        if (isset($data['filter_confirm']) && $data['filter_confirm'] > -1) {
            if(is_array($data['filter_confirm'])){
                $query = $query->whereIn('filter_confirm', $data['filter_confirm']);
            }else{
                $query = $query->where('filter_confirm', $data['filter_confirm']);
            }  
        }
        if (isset($data['is_order_care'])) {
            if(is_array($data['is_order_care'])){
                $query = $query->whereIn('is_order_care', $data['is_order_care']);
            }else{
                $query = $query->where('is_order_care', $data['is_order_care']);
            }  
        }
        if (isset($data['filter_ship']) && $data['filter_ship'] > -1) {
            if(is_array($data['filter_ship'])){
                $query = $query->whereIn('filter_ship', $data['filter_ship']);
            }else{
                $query = $query->where('filter_ship', $data['filter_ship']);
            }  
        }
        if (isset($data['reason']) && $data['reason'] > -1) {
            if(is_array($data['reason'])){
                $data['reason'] = array_map(
                    function($value) { if ($value != "-1") return $value; },
                    $data['reason']
                );
                $query = $query->whereIn('reason', $data['reason']);
            }else{
                $query = $query->where('reason', $data['reason']);
            }
        }
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

        if (isset($data['type_confirm_text']) && (isset($data['time_start']) || isset($data['time_end']))) {
            $query = $query->where('type_confirm_text', $data['type_confirm_text']);
        }
        if (isset($data['sortBy']) && $data['sortBy'] != '') {
            $query = $query->orderBy($data['sortBy'], isset($data['sortOrder']) ? $data['sortOrder'] : 'DESC');
        } else {
            $query = $query->orderBy('_id', 'DESC');
        }

        return $query;
    }

    public function searchPhone($data)
    {
        $query = $this->result;
        $query = $query->select('user_create_id', 'name', 'phone', 'product_id', 'address', 'created_at');
        if(is_array($data['phone'])){
            $query = $query->whereIn('phone', $data['phone']);
        }else{
            $query = $query->where('phone', $data['phone']);
        }
        $query = $query->where('company_id', $data['company_id']);
        $query = $query->orderBy('_id', 'DESC');
        return $query->get();
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

    public function all()
    {
        $query = $this->result->all();
        return $query;
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

    public function get($data)
    {
        return $this->query($data)->get();
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
    
    public function updateByOrderIdArray($orderIds, $replace)
    {
        $query = $this->result;
        $query = $query->whereIn('_id', $orderIds)->get();
        if ($query) {
            foreach ($query as $res) {
                try {
                    foreach ($replace as $key => $value) {
                        $res->$key = $value;
                    }
                    $res->save();
                } catch (Exception  $e) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    public function removeById($data)
    {
        $query = $this->result;
        if(is_array($data['_id'])){
            $query = $query->whereIn('_id', $data['_id']);
        }else{
            $query = $query->where('_id', $data['_id']);
        }
        $query = $query->delete();
        return $query;
    }

    public function updateById($id, $data)
    {
        $target = $this->firstById($id);
        try {
            foreach ($data as $key => $value) {
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

    public function getById($id)
    {
        return $this->result->where('_id', $id)->get();
    }

    public function getByCompanyId($companyId, $limit = null)
    {
        $query = $this->result;
        if(is_array($companyId)){
            $query = $query->whereIn('company_id', $companyId);
        }else{
            $query = $query->where('company_id', $companyId);
        }
        if ($limit){
            $query = $query->take($limit);
        }
        return $query->orderBy('_id','DESC')->get();
    }


    public function getNewDataCustomerLimitNumber($limit = 10)
    {
        $result = $this->result;
        $result = $result->take($limit);
        $result = $result->orderBy('updated_at', 'DESC');
        return $result->get();
    }

    public function getUserReciverByCustomerId($customerId)
    {
        $result = $this->result;
        $result = $result->where('user_reciver_id', $customerId);
        $result = $result->orderBy('updated_at', 'DESC');
        return $result->get()->pluck('_id');
    }

    public function getInMonthForMkt($customer, $month, $request)
    {
        $year = date('Y');
        $dateTime = $year . '-' . $month . '-' . 01;
        $startOfMonth = Carbon::parse($dateTime)->startOfMonth();
        $endOfMonth = Carbon::parse($dateTime)->endOfMonth();
        $result = $this->result;
        $result = $result->where('user_create_id', $customer->_id);
        $result = $result->where('created_at', '>=', $startOfMonth);
        $result = $result->where('created_at', '<=', $endOfMonth);
        if (!empty($request['product_id']) && $request['product_id'] != -1) {
            $result = $result->where('product_id', $request['product_id']);
        }
        return $result->get();
    }

    public function getInMonthForSale($customer, $month, $request)
    {
        $year = date('Y');
        $dateTime = $year . '-' . $month . '-' . 01;
        $startOfMonth = Carbon::parse($dateTime)->startOfMonth();
        $endOfMonth = Carbon::parse($dateTime)->endOfMonth();
        $result = $this->result;
        $result = $result->where('user_reciver_id', $customer->_id);
        $result = $result->where('date_reciver', '>=', $startOfMonth);
        $result = $result->where('date_reciver', '<=', $endOfMonth);
        if (!empty($request['product_id']) && $request['product_id'] != -1) {
            $result = $result->where('product_id', $request['product_id']);
        }
        return $result->get();
    }

    public function getByUserCreateId($customer)
    {
        $result = $this->result;
        $result = $result->where('user_create_id', $customer->_id);
        return $result->get();
    }

    public function getByUserReciverId($customer)
    {
        $result = $this->result;
        $result = $result->where('user_reciver_id', $customer->_id);
        return $result->get();
    }

    //==== PRODUCT  =====//

    public function getByStatusActive()
    {
        $query = $this->listProduct;
        $query = $query->where('status', 1);
        return $query->get();
    }

    public function getProductById($productId)
    {
        $query = $this->listProduct;
        if(is_array($productId)){
            $query = $query->whereIn('_id', $productId);
        }else{
            $query = $query->where('_id', $productId);
        }
        $query = $query->where('status', 1);
        return $query->get();
    }

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
        if(is_array($id)){
            $query = $query->whereIn('company_id', $id);
        }else{
            $query = $query->where('company_id', $id);
        }
        return $query->get();
    }
}
