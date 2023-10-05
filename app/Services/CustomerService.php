<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Model\Mongo\CustomerModel;
use App\Model\Mongo\CustomerActivityModel;
use Exception;
use Carbon\Carbon;

class CustomerService
{

  public function __construct(CustomerModel $customerModel, CustomerActivityModel $customerActivityModel)
  {
    $this->customer = $customerModel;
    $this->customerActivityModel = $customerActivityModel;
  }

  public function info()
  {
    return Auth::user();
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

  public function create($data)
  {
    try {
      $query = $this->customer;
      foreach ($data as $key => $value) {
        $query->$key = $value;
      }
      $query->save();
      return $query;
    } catch (Exception  $e) {
      throw $e;
    }
  }

  public function remove($id)
  {
    $query = $this->customer;
    if (is_array($id)) {
      $query = $query->whereIn('_id', $id);
    } else {
      $query = $query->where('_id', $id);
    }
    $query = $query->delete();
    return $query;
  }

  public function updateById($ids, $replace)
  {
    $query = $this->customer;
        $query = $query->whereIn('_id', $ids)->get();
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

  public function createActivity($data)
  {
    $query = $this->customerActivityModel;
    foreach ($data as $key => $value) {
        $query->$key = $value;
    }
    $query->save();
    return true;
  }

  
  public function get($data)
  {
    $query = $this->customer;
    foreach ($data as $key => $value) {
      if (is_array($value)) {
        $query = $query->whereIn($key, $value);
      } else {
        $query = $query->where($key, $value);
      }
    }
    $data = $query->get();
    return $data;
  }

  public function getByCompanyId($companyId)
  {
    $query = $this->customer;
    if (is_array($companyId)) {
      $query = $query->whereIn('company_id', $companyId);
    }else{
      $query = $query->where('company_id', $companyId);
    }
    
    $query = $query->where('status', 1);
    return $query->get();
  }

  public function getAllByCompanyId($companyId)
  {
    $query = $this->customer;
    if (is_array($companyId)) {
      $query = $query->whereIn('company_id', $companyId);
    }else{
      $query = $query->where('company_id', $companyId);
    }
    $query = $query->where('status', 1);
    $query = $query->orderBy('_id', 'DESC');
    return $query->get();
  }

  public function queryAllByCompanyId($companyId, $data)
  {
    $query = $this->customer;
    if (is_array($companyId)) {
      $query = $query->whereIn('company_id', $companyId);
    }else{
      $query = $query->where('company_id', $companyId);
    }
    if (isset($data['search']) && $data['search'] != '') {
        $query->where(function ($q) use ($data) {
            $q->orWhere('customer_id', 'like', '%' . $data['search'] . '%');
            $q->orWhere('name', 'like', '%' . $data['search'] . '%');
            $q->orWhere('phone', 'like', '%' . $data['search'] . '%');
            $q->orWhere('email', 'like', '%' . $data['search'] . '%');

        });
    }

    $query = $query->where('status', 1);
    $query = $query->orderBy('_id', 'DESC');
    return $query;
  }

  public function getReportAllBilling($customer, $request)
  {
    $request['time_start'] = Carbon::parse($request['time_start'])->startOfDay();
    $request['time_end'] = Carbon::parse($request['time_end'])->endOfDay();

    $users = $this->getAllByCompanyId($customer->company_id);
    if ($this->isMkt()){
      $users = $users->load(['orderByMkt.ship' => function ($q) use ($request) {
        $q->where('created_at', '>=', $request['time_start']);
        $q->where('created_at', '<=', $request['time_end']);
      }]);
    }else{
      $users = $users->load(['orderBySale.ship' => function ($q) use ($request) {
        $q->where('created_at', '>=', $request['time_start']);
        $q->where('created_at', '<=', $request['time_end']);
      }]);
    }
    return $users;
  }

  public function getReportAllBillingSaleFromMkt($companyId, $request)
  {
    $request['time_start'] = Carbon::parse($request['time_start'])->startOfDay();
    $request['time_end'] = Carbon::parse($request['time_end'])->endOfDay();
    $users = $this->getAllByCompanyId($companyId);
    $users = $users->load(['orderBySale.ship' => function ($q) use ($request) {
      $q->where('created_at', '>=', $request['time_start']);
      $q->where('created_at', '<=', $request['time_end']);
    }]);
    return $users;
  }

  public function firstByUsername($username)
  {
    $query = $this->customer;
    $query = $query->where('username', $username);
    return $query->first();
  }

  public function first($id, $select = null)
  {
    $query = $this->customer;
    $query = $query->where('_id', $id);
    if ($select) {
      $query = $query->select($select);
    }
    return $query->first();
  }

  public function firstById($id)
  {
      return $this->customer->find($id);
  }

  public function getById($id)
  {
    $query = $this->customer;
    if (is_array($id)) {
      $query = $query->whereIn('_id', $id);
    } else {
      $query = $query->where('_id', $id);
    }
    $query = $query->where('status', 1);
    return $query->get();
  }

  public function getSaleByCompanyId($id)
  {
    $query = $this->customer;
    if (is_array($id)) {
      $query = $query->whereIn('company_id', $id);
    } else {
      $query = $query->where('company_id', $id);
    }
    $query = $query->where('type_account', 'sale');
    $query = $query->where('status', 1);
    return $query->get();
  }

  public function getMktByCompanyId($id)
  {
    $query = $this->customer;
    if (is_array($id)) {
      $query = $query->whereIn('company_id', $id);
    } else {
      $query = $query->where('company_id', $id);
    }
    $query = $query->where('type_account', 'mkt');
    $query = $query->where('status', 1);
    return $query->get();
  }

  public function searchPhone($data)
    {
        $query = $this->customer;
        $query = $query->select('name', 'phone', 'email', 'address', 'note', 'created_at');
        if(is_array($data['phone'])){
            $query = $query->whereIn('phone', $data['phone']);
        }else{
            $query = $query->where('phone', $data['phone']);
        }
        $query = $query->where('company_id', $data['company_id']);
        $query = $query->orderBy('_id', 'DESC');
        return $query->get();
    }
 
}
