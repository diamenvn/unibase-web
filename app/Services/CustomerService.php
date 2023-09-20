<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Model\Mongo\CustomerModel;
use Exception;
use Carbon\Carbon;

class CustomerService
{

  public function __construct(CustomerModel $customerModel)
  {
    $this->customer = $customerModel;
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
  public function isUserMkt()
  {
    return $this->info()->type_account == "mkt" && $this->info()->permission == "user";
  }

  public function isUserSale()
  {
    return $this->info()->type_account == "sale" && $this->info()->permission == "user";
  }

  public function isSale()
  {
    return $this->info()->type_account == "sale";
  }

  public function isAdminSale()
  {
    return $this->info()->type_account == "sale" && $this->isAdmin();
  }

  public function isAdminMkt()
  {
    return $this->info()->type_account == "mkt" && $this->isAdmin();;
  }

  public function isMkt()
  {
    return $this->info()->type_account == "mkt";
  }

  public function isAdmin()
  {
    return $this->info()->permission == "admin" || $this->isSuperAdmin();
  }

  public function isVandon()
  {
    return $this->info()->type_account == "bill";
  }

  public function isUser()
  {
    return $this->info()->permission == "user";
  }

  public function isSuperAdmin()
  {
    return $this->info()->permission == "super";
  }

  public function isCare()
  {
    return $this->info()->type_account == "care";
  }

  public function updateUser()
  {
  }

  public function createUser()
  {
  }

  public function deleteUser()
  {
  }
}
