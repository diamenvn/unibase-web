<?php

namespace App\Http\Controllers\Site;

use App\Helpers\App;
use App\Http\Controllers\Controller;
use App\Services\ActionActivityService;
use App\Services\CustomerService;
use App\Services\OrderActivityService;
use App\Services\OrderCareService;
use App\Services\OrderListService;
use App\Services\SettingService;
use Carbon\Carbon;

class SiteOrderController extends Controller
{
  public function __construct(CustomerService $customer, OrderListService $order, OrderCareService $orderCareService, OrderActivityService $activity, ActionActivityService $action, SettingService $setting)
  {
    $this->customer = $customer;
    $this->order = $order;
    $this->orderCareService = $orderCareService;
    $this->activity = $activity;
    $this->action = $action;
    $this->setting = $setting;
    $this->timeNow = Carbon::now();
  }

  public function list()
  {
    $customer = $this->customer->info()->load('customer')->load('company');

    
    if ($customer->company->company_type == "all") {
      $userSale = $this->customer->getSaleByCompanyId($customer->company_id);
      $userMKT = $this->customer->getMktByCompanyId($customer->company_id);
      $sources = $this->order->getListSourceByCompanyId($customer->company_id)->load('source');

    } else {

      if ($this->customer->isMkt()) {
        $sources = $this->order->getListSourceByCompanyId($customer->company_id)->load('source');
        $companyId = $customer->load('companyMkt')->companyMkt->pluck('company_sale_id')->toArray();
        $userSale = $this->customer->getByCompanyId($companyId);
      } else {
        $companyId = $customer->load('companySale')->companySale->pluck('company_mkt_id')->toArray();
        $sources = $this->order->getListSourceByCompanyId($companyId)->load('source');
        $userSale = $this->customer->getByCompanyId($customer->company_id);
      }
      $userMKT = $this->customer->getByCompanyId($customer->company_id);
    }

    $companyConnect = $this->loadListCompanyConnect($customer);

    $actions = $this->action->get(['status' => 1]);
    return view('site.order.grid')
      ->with('info', $userSale)
      ->with('userSale', $userSale)
      ->with('userMKT', $userMKT)
      ->with('companyConnect', $companyConnect)
      ->with('actions', $actions)
      ->with('sources', $sources);
  }

  public function lading()
  {
    $customer = $this->customer->info()->load('customer');
    if (!$this->customer->isAdmin() && !$this->customer->isVandon()) {
      return abort(404);
    }
    if ($this->customer->isMkt()) {
      $sources = $this->order->getListSourceByCompanyId($customer->company_id)->load('source');
      $companyIdConnect = $customer->load('companyMkt')->companyMkt->pluck('company_sale_id')->toArray();
      $userCompany = $this->customer->getByCompanyId($companyIdConnect);
    } else {
      $companyId = $customer->load('companySale')->companySale->pluck('company_mkt_id')->toArray();
      $sources = $this->order->getListSourceByCompanyId($companyId)->load('source');
      $userCompany = $this->customer->getByCompanyId($customer->company_id);
    }


    $companyConnect = $this->loadListCompanyConnect($customer);

    $actions = $this->action->get(['status' => 1]);
    return view('site.order.ship')
      ->with('info', $customer)
      ->with('userCompany', $userCompany)
      ->with('companyConnect', $companyConnect)
      ->with('actions', $actions)
      ->with('sources', $sources);
  }

  public function care()
  {
    $customer = $this->customer->info()->load('customer');
    if (!$this->customer->isAdmin() && !$this->customer->isVandon()) {
      return abort(404);
    }
    if ($this->customer->isMkt()) {
      $sources = $this->order->getListSourceByCompanyId($customer->company_id)->load('source');
      $companyIdConnect = $customer->load('companyMkt')->companyMkt->pluck('company_sale_id')->toArray();
      $userCompany = $this->customer->getByCompanyId($companyIdConnect);
    } else {
      $companyId = $customer->load('companySale')->companySale->pluck('company_mkt_id')->toArray();
      $sources = $this->order->getListSourceByCompanyId($companyId)->load('source');
      $userCompany = $this->customer->getByCompanyId($customer->company_id);
    }


    $companyConnect = $this->loadListCompanyConnect($customer);

    $actions = $this->action->get(['status' => 1]);
    return view('site.lading.ship_care')
      ->with('info', $customer)
      ->with('userCompany', $userCompany)
      ->with('companyConnect', $companyConnect)
      ->with('actions', $actions)
      ->with('sources', $sources);
  }

  public function detail($id)
  {
    $customer = $this->customer->info();
    $order = $this->order->firstById($id) ?? $this->orderCareService->firstById($id);
    if (empty($order)) {
      return abort(404);
    }

    $urlBack = (string) url()->previous();

    $permission = $this->checkPermission($order);

    if (!$permission) {
      return abort(404);
    }

    if ($this->customer->isCare() || $this->customer->isUserSale()) {
      $this->updateOrder($customer, $order);
    }

    
    
    $order->load('product')
        ->load('source')
        ->load('activity')
        ->load('activityCare')
        ->load('company')
        ->load('companyProduct')
        ->load('companySource')
        ->load('companyCustomer')
        ->load('ship')
        ->load('customerCreateOrder');
  
    $actions = $this->action->get(['status' => 1]);

    return view('site.order.detail')
      ->with('actions', $actions)
      ->with('urlBack', $urlBack)
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

  public function myList()
  {
    $customer = $this->customer->info();
    if ($this->customer->isMkt()) {
      $productId = $this->order->getListProductByCompanyId($customer->company_id)
        ->load('product')
        ->pluck('product_id')
        ->toArray();
    } else {
      $productId = $customer->load('companySale')->companySale
        ->pluck('product_id')
        ->toArray();
    }
    $actions = $this->action->get(['status' => 1]);
    $product = $this->order->getProductById($productId);

    return view('site.order.mylist')
      ->with('actions', $actions)
      ->with('product', $product);
  }

  public function customerCare()
  {
    $customer = $this->customer->info()->load('customer')->load('company');

    
    if ($customer->company->company_type == "all") {
      $userSale = $this->customer->getSaleByCompanyId($customer->company_id);
      $userMKT = $this->customer->getMktByCompanyId($customer->company_id);
      $sources = $this->order->getListSourceByCompanyId($customer->company_id)->load('source');

    } else {

      if ($this->customer->isMkt()) {
        $sources = $this->order->getListSourceByCompanyId($customer->company_id)->load('source');
        $companyId = $customer->load('companyMkt')->companyMkt->pluck('company_sale_id')->toArray();
        $userSale = $this->customer->getByCompanyId($companyId);
      } else {
        $companyId = $customer->load('companySale')->companySale->pluck('company_mkt_id')->toArray();
        $sources = $this->order->getListSourceByCompanyId($companyId)->load('source');
        $userSale = $this->customer->getByCompanyId($customer->company_id);
      }
      $userMKT = $this->customer->getByCompanyId($customer->company_id);
    }

    $companyConnect = $this->loadListCompanyConnect($customer);

    $actions = $this->action->get(['status' => 1]);
    return view('site.order.care')
      ->with('info', $userSale)
      ->with('userSale', $userSale)
      ->with('userMKT', $userMKT)
      ->with('companyConnect', $companyConnect)
      ->with('actions', $actions)
      ->with('sources', $sources);
  }

  public function loadListCompanyConnect($customer)
  {
    if ($this->customer->isSale()) {
      return $this->setting->getCompanyConnectFromSale($customer->company_id);
    } else {
      return [];
    }
  }

  public function checkPermission($order)
  {
    $customer = $this->customer->info();
    $check = App::filterByPermissionCustomer($customer, []);

    return (in_array($order->_id, $this->order->search($check)->get()->pluck('_id')->toArray()) || in_array($order->_id, $this->orderCareService->search($check)->get()->pluck('_id')->toArray()));
    
  }


  public function updateOrder($customer, $order)
  {
    if (isset($order->is_order_care) && $order->is_order_care == true) {
      if (empty($order->date_reciver)) {
        $data['date_reciver'] = $this->timeNow;
        $data['user_care_id'] = $customer->_id;
        $this->orderCareService->update($order, $data);
      }
    }else{
      if ($this->customer->isUserSale()) {
        if (empty($order->date_reciver)) {
          $data['date_reciver'] = $this->timeNow;
          $data['user_reciver_id'] = $customer->_id;
          $this->order->update($order, $data);
        }
      }
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
