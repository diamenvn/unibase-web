<?php

namespace App\Http\Controllers\Site;

use App\Helpers\App;
use App\Http\Controllers\Controller;
use App\Services\ActionActivityService;
use App\Services\UserService;
use App\Services\OrderActivityService;
use App\Services\OrderCareService;
use App\Services\OrderListService;
use App\Services\SettingService;
use Carbon\Carbon;

class SiteOrderController extends Controller
{
  public function __construct(UserService $user, OrderListService $order, OrderCareService $orderCareService, OrderActivityService $activity, ActionActivityService $action, SettingService $setting)
  {
    $this->user = $user;
    $this->order = $order;
    $this->orderCareService = $orderCareService;
    $this->activity = $activity;
    $this->action = $action;
    $this->setting = $setting;
    $this->timeNow = Carbon::now();
  }

  public function list()
  {
    $user = $this->user->info()->load('customer')->load('company');

    
    if ($user->company->company_type == "all") {
      $userSale = $this->user->getSaleByCompanyId($user->company_id);
      $userMKT = $this->user->getMktByCompanyId($user->company_id);
      $sources = $this->order->getListSourceByCompanyId($user->company_id)->load('source');

    } else {

      if ($this->user->isMkt()) {
        $sources = $this->order->getListSourceByCompanyId($user->company_id)->load('source');
        $companyId = $user->load('companyMkt')->companyMkt->pluck('company_sale_id')->toArray();
        $userSale = $this->user->getByCompanyId($companyId);
      } else {
        $companyId = $user->load('companySale')->companySale->pluck('company_mkt_id')->toArray();
        $sources = $this->order->getListSourceByCompanyId($companyId)->load('source');
        $userSale = $this->user->getByCompanyId($user->company_id);
      }
      $userMKT = $this->user->getByCompanyId($user->company_id);
    }

    $companyConnect = $this->loadListCompanyConnect($user);

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
    $user = $this->user->info()->load('customer');
    if (!$this->user->isAdmin() && !$this->user->isVandon()) {
      return abort(404);
    }
    if ($this->user->isMkt()) {
      $sources = $this->order->getListSourceByCompanyId($user->company_id)->load('source');
      $companyIdConnect = $user->load('companyMkt')->companyMkt->pluck('company_sale_id')->toArray();
      $userCompany = $this->user->getByCompanyId($companyIdConnect);
    } else {
      $companyId = $user->load('companySale')->companySale->pluck('company_mkt_id')->toArray();
      $sources = $this->order->getListSourceByCompanyId($companyId)->load('source');
      $userCompany = $this->user->getByCompanyId($user->company_id);
    }


    $companyConnect = $this->loadListCompanyConnect($user);

    $actions = $this->action->get(['status' => 1]);
    return view('site.order.ship')
      ->with('info', $user)
      ->with('userCompany', $userCompany)
      ->with('companyConnect', $companyConnect)
      ->with('actions', $actions)
      ->with('sources', $sources);
  }

  public function care()
  {
    $user = $this->user->info()->load('customer');
    if (!$this->user->isAdmin() && !$this->user->isVandon()) {
      return abort(404);
    }
    if ($this->user->isMkt()) {
      $sources = $this->order->getListSourceByCompanyId($user->company_id)->load('source');
      $companyIdConnect = $user->load('companyMkt')->companyMkt->pluck('company_sale_id')->toArray();
      $userCompany = $this->user->getByCompanyId($companyIdConnect);
    } else {
      $companyId = $user->load('companySale')->companySale->pluck('company_mkt_id')->toArray();
      $sources = $this->order->getListSourceByCompanyId($companyId)->load('source');
      $userCompany = $this->user->getByCompanyId($user->company_id);
    }


    $companyConnect = $this->loadListCompanyConnect($user);

    $actions = $this->action->get(['status' => 1]);
    return view('site.lading.ship_care')
      ->with('info', $user)
      ->with('userCompany', $userCompany)
      ->with('companyConnect', $companyConnect)
      ->with('actions', $actions)
      ->with('sources', $sources);
  }

  public function detail($id)
  {
    $user = $this->user->info();

    $steps = $this->order->getListLabelByCompanyId($user->company_id)->load('order');
    $order = $this->order->firstById($id) ?? $this->orderCareService->firstById($id);
    if (empty($order)) {
      return abort(404);
    }

    $urlBack = (string) url()->previous();

    $permission = $this->checkPermission($order);

    if (!$permission) {
      return abort(404);
    }

    if ($this->user->isCare() || $this->user->isUserSale()) {
      $this->updateOrder($user, $order);
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
      ->with('steps', $steps)
      ->with('order', $order);
  }

  public function create()
  {
    $info = $this->user->info()
      ->load('product')
      ->load('company')
      ->load('customer_new')
      ->load('source');

    return view('site.order.create')
      ->with('info', $info);
  }

  public function myList()
  {
    $user = $this->user->info();
    if ($this->user->isMkt()) {
      $productId = $this->order->getListProductByCompanyId($user->company_id)
        ->load('product')
        ->pluck('product_id')
        ->toArray();
    } else {
      $productId = $user->load('companySale')->companySale
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
    $user = $this->user->info()->load('customer')->load('company');

    
    if ($user->company->company_type == "all") {
      $userSale = $this->user->getSaleByCompanyId($user->company_id);
      $userMKT = $this->user->getMktByCompanyId($user->company_id);
      $sources = $this->order->getListSourceByCompanyId($user->company_id)->load('source');

    } else {

      if ($this->user->isMkt()) {
        $sources = $this->order->getListSourceByCompanyId($user->company_id)->load('source');
        $companyId = $user->load('companyMkt')->companyMkt->pluck('company_sale_id')->toArray();
        $userSale = $this->user->getByCompanyId($companyId);
      } else {
        $companyId = $user->load('companySale')->companySale->pluck('company_mkt_id')->toArray();
        $sources = $this->order->getListSourceByCompanyId($companyId)->load('source');
        $userSale = $this->user->getByCompanyId($user->company_id);
      }
      $userMKT = $this->user->getByCompanyId($user->company_id);
    }

    $companyConnect = $this->loadListCompanyConnect($user);

    $actions = $this->action->get(['status' => 1]);
    return view('site.order.care')
      ->with('info', $userSale)
      ->with('userSale', $userSale)
      ->with('userMKT', $userMKT)
      ->with('companyConnect', $companyConnect)
      ->with('actions', $actions)
      ->with('sources', $sources);
  }

  public function loadListCompanyConnect($user)
  {
    if ($this->user->isSale()) {
      return $this->setting->getCompanyConnectFromSale($user->company_id);
    } else {
      return [];
    }
  }

  public function checkPermission($order)
  {
    $user = $this->user->info();
    $check = App::filterByPermissionCustomer($user, []);

    return (in_array($order->_id, $this->order->search($check)->get()->pluck('_id')->toArray()) || in_array($order->_id, $this->orderCareService->search($check)->get()->pluck('_id')->toArray()));
    
  }


  public function updateOrder($user, $order)
  {
    if (isset($order->is_order_care) && $order->is_order_care == true) {
      if (empty($order->date_reciver)) {
        $data['date_reciver'] = $this->timeNow;
        $data['user_care_id'] = $user->_id;
        $this->orderCareService->update($order, $data);
      }
    }else{
      if ($this->user->isUserSale()) {
        if (empty($order->date_reciver)) {
          $data['date_reciver'] = $this->timeNow;
          $data['user_reciver_id'] = $user->_id;
          $this->order->update($order, $data);
        }
      }
    }
  }

  public function setActivityLogOrder($order, $note, $status = 1)
  {
    $data['user_id'] = $this->user->info()->_id;
    $data['order_id'] = $order->_id;
    $data['note'] = $note;
    $data['status'] = $status;
    $this->activity->create($data);
  }
}
