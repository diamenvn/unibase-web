<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use App\Services\OrderActivityService;
use App\Services\OrderListService;
use App\Services\SettingService;
use App\Services\RequestService;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Location;

class SiteHomeController extends Controller
{
  public function __construct(
    CustomerService $customer,
    OrderActivityService $orderActivity,
    OrderListService $orderList,
    SettingService $setting,
    RequestService $requestService
  )
  {
    $this->customer = $customer;
    $this->activity = $orderActivity;
    $this->order = $orderList;
    $this->setting = $setting;
    $this->requestService = $requestService;
  }

  public function home()
  {
    return redirect()->route('auth.login');
  }

  public function welcome()
  {
    $location = $this->getLocationByIP();
    return view('site.welcome.index')
    ->with('location', $location);
  }
  

  public function dashboard()
  {
    $customer = $this->customer->info();
    
    $activity = $this->getActivityByCustomer($customer);
    
    $newCustomer = $this->getNewData($customer);

    return view('site.home.dashboard')
      ->with('customer', $customer)
      ->with('activity', $activity)
      ->with('newCustomer', $newCustomer);
  }

  public function getActivityByCustomer($customer)
  {
    $activity = [];
    $limit = 10;
    $company = [];
    if ($this->customer->isMkt()) {
      $company = $customer->load('companyMkt')->companyMkt->pluck('company_sale_id')->toArray();
      
    }
    array_push($company, $customer->company_id);
    // dd($company);
    $userId = $this->customer->getByCompanyId($company)->pluck('_id')->toArray();
    if ($this->customer->isAdmin()) {
      $activity = $this->activity->getByUserId($userId)->where('group', '!=', 2)->take($limit);
    } else {
      $activity = $customer->load('activity')->activity->where('group', '!=', 2)->take($limit);
    }
    return $activity;
  }

  public function getNewData($customer)
  {
    if ($this->customer->isMkt()){
      $companys =  $customer->company_id;
    }else{
      $companys = $this->setting->getCompanyConnectFromSale($customer->company_id)->pluck('company_mkt_id')->toArray();
    }
    return $this->order->getByCompanyId($companys, 10 /* limit */);
  }

  public function getLocationByIP()
  {
    $ip = Location::getClientIP();
    $token = config("ipinfo.token");
    $uri = "ipinfo.io/" . $ip . "?token=" . $token;
    $sendRequest = $this->requestService->send($uri);
    return $sendRequest;
  }
}
