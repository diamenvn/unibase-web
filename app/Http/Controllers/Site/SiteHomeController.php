<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\OrderActivityService;
use App\Services\OrderListService;
use App\Services\SettingService;
use App\Services\RequestService;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Location;

class SiteHomeController extends Controller
{
  public function __construct(
    UserService $user,
    OrderActivityService $orderActivity,
    OrderListService $orderList,
    SettingService $setting,
    RequestService $requestService
  )
  {
    $this->user = $user;
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
    $user = $this->user->info();
    
    $activity = $this->getActivityByCustomer($user);
    
    $newCustomer = $this->getNewData($user);

    return view('site.home.dashboard')
      ->with('customer', $user)
      ->with('activity', $activity)
      ->with('newCustomer', $newCustomer);
  }

  public function getActivityByCustomer($user)
  {
    $activity = [];
    $limit = 10;
    $company = [];
    if ($this->user->isMkt()) {
      $company = $user->load('companyMkt')->companyMkt->pluck('company_sale_id')->toArray();
      
    }
    array_push($company, $user->company_id);
    // dd($company);
    $userId = $this->user->getByCompanyId($company)->pluck('_id')->toArray();
    if ($this->user->isAdmin()) {
      $activity = $this->activity->getByUserId($userId)->where('group', '!=', 2)->take($limit);
    } else {
      $activity = $user->load('activity')->activity->where('group', '!=', 2)->take($limit);
    }
    return $activity;
  }

  public function getNewData($user)
  {
    if ($this->user->isMkt()){
      $companys =  $user->company_id;
    }else{
      $companys = $this->setting->getCompanyConnectFromSale($user->company_id)->pluck('company_mkt_id')->toArray();
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
