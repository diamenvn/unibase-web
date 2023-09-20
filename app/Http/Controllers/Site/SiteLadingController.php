<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\ActionActivityService;
use App\Services\CustomerService;
use App\Services\OrderActivityService;
use App\Services\OrderListService;
use App\Services\SettingService;

class SiteLadingController extends Controller
{
  public function __construct(CustomerService $customer, OrderListService $order, OrderActivityService $activity, ActionActivityService $action, SettingService $setting)
  {
    $this->customer = $customer;
    $this->order = $order;
    $this->activity = $activity;
    $this->action = $action;
    $this->setting = $setting;
  }

  public function list()
  {
    $customer = $this->customer->info()->load('customer');
    if (!$this->customer->isAdminSale() || !$this->customer->isVandon()){
      return abort(404);
    }

    $groups = $this->getMembersGroupByCustomer($customer);
    $userCompany = $this->customer->getById($groups);
    $companyConnect = $this->loadListCompanyConnect($customer);
   
    $actions = $this->action->get(['status' => 1]);
    return view('site.lading.list')
      ->with('info', $customer)
      ->with('userCompany', $userCompany)
      ->with('companyConnect', $companyConnect)
      ->with('actions', $actions);
  }

  public function detail($id)
  {
    $order = $this->order->firstById($id);
    if (empty($order)) {
      return abort(404);
    }

    $order->load('ship');

    return view('site.order.detail')
      ->with('order', $order);
  }

  public function create()
  {
    $info = $this->customer->info()
      ->load('product')
      ->load('company')
      ->load('source');

    return view('site.order.create')
      ->with('info', $info);
  }

  public function loadListCompanyConnect($customer)
  {
    if ($this->customer->isSale()) {
      return $this->setting->getCompanyConnectFromSale($customer->company_id);
    }else{
      return [];
    }
  }

  public function checkPermissionCustomer($order)
  {
    $customer = $this->customer->info();

    if ($this->customer->isUserSale()) {
      $groups = $this->getMembersGroupByCustomer($customer);
      $exist = !empty(array_intersect($order->user_reciver_id, $groups));
      return $exist;
    } elseif ($this->customer->isUserMkt()) {
      $groups = $this->getMembersGroupByCustomer($customer);
      $exist = in_array($customer->_id, $groups);
      return $exist;
    } elseif ($this->customer->isAdminMkt()) {
      return $order->company_id == $customer->company_id;
    } else {
      $connect = $customer->load('companySale')->companySale;
      $productsId = $connect->pluck('product_id')->toArray();
      $companysId = $connect->pluck('company_mkt_id')->toArray();
      return in_array($order->product_id, $productsId) && in_array($order->company_id, $companysId);
    }
  }

  public function updateOrder($order)
  {
    $data['proccessing'] = true;
    if (empty($order->date_reciver)) {
      $data['date_reciver'] = time();
    }
    $this->order->update($order, $data);
  }

  public function getMembersGroupByCustomer($customer)
  {
    $group = $this->setting->findGroupByCustomerId($customer->_id)->pluck('members');
    $group->push(array($customer->_id));
    $groups = $this->arrayMerge($group->toArray());
    return $groups;
  }

  public function arrayMerge($array)
  {
    if (!empty($array)) {
      return array_unique(array_merge(...$array));
    } else {
      return [];
    }
  }

  public function setActivityLogOrder($order, $note, $status = 1)
  {
    $data['user_id'] = $this->customer->info()->_id;
    $data['order_id'] = $order->_id;
    $data['note'] = $note;
    $data['status'] = $status;
    $this->activity->create($data);
  }
}
