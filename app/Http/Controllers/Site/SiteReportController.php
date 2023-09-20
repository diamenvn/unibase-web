<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\ActionActivityService;
use App\Services\CustomerService;
use App\Services\OrderActivityService;
use App\Services\OrderListService;
use App\Services\SettingService;

class SiteReportController extends Controller
{
  public function __construct(CustomerService $customer, OrderListService $order, OrderActivityService $activity, ActionActivityService $action, SettingService $setting)
  {
    $this->customer = $customer;
    $this->order = $order;
    $this->activity = $activity;
    $this->action = $action;
    $this->setting = $setting;
  }

  public function billing()
  {
    $customer = $this->customer->info();

    if ($this->customer->isUser()) {
      abort(404);
    }

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
    $product = $this->order->getProductById($productId);
    return view('site.report.billing')
      ->with('product', $product);
  }

  public function personal()
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
    $product = $this->order->getProductById($productId);
    return view('site.report.personal')
      ->with('product', $product);
  }

  public function billingSale()
  {
    $customer = $this->customer->info();

    if ($this->customer->isUser()) {
      abort(404);
    }

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
    $product = $this->order->getProductById($productId);
    return view('site.report.billingSale')
      ->with('product', $product);
  }

  public function note()
  {
    $customer = $this->customer->info();

    $listArrayCompanyId = [];
    if ($this->customer->isAdmin()) {
      array_push($listArrayCompanyId, $customer->company_id);

      if ($this->customer->isMkt()) {
        $company = $this->arrayUnique($customer->load('companyMkt')->companyMkt->pluck('company_sale_id')->toArray());
      } else {
        $company = $this->arrayUnique($customer->load('companySale')->companyMkt->pluck('company_mkt_id')->toArray());
      }

      foreach ($company as $value) {
        array_push($listArrayCompanyId, $value);
      }
      $listUsers = $this->customer->getAllByCompanyId($listArrayCompanyId);
    } else {
      $users = $this->getMembersGroupByCustomer($customer);
      $listUsers = $this->customer->getById($users);
    }

    

    return view('site.report.note')
      ->with('listUsers', $listUsers);
  }

  public function arrayUnique($array)
  {
    if (!empty($array)) {
      return array_unique($array);
    } else {
      return [];
    }
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
}
