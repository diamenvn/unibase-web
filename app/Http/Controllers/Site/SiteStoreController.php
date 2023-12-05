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
use Illuminate\Http\Request;
use Carbon\Carbon;

class SiteStoreController extends Controller
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
    $data['route_list'] = route('api.store.list');
    $data['tabs'] = $this->getListTabs();
    $data['form'] = route('site.store.form');

    return view('site.store.list', $data);
  }

  public function form(Request $request)
  {
    $request = $request->only("app", "step");
    $app = $request['app'] ?? "";
    $step = $request['step'] ?? "1";
    $data["sso_url"] = "";
    $fileViewName = "site.store.add.step" . $step;
    
    if ($app == "tiktok") {
      $data["sso_url"] = config("url.tiktok-sso-uri");
    }

    return view($fileViewName, $data);
  }

  public function getListTabs()
  {
    return [
        [
            'title' => 'Tất cả',
            'value' => 'all',
        ],
        [
            'title' => 'Tiktok',
            'value' => 'tiktok',
        ],
        [
            'title' => 'Shopee',
            'value' => 'shopee',
        ],
        [
            'title' => 'Etsy',
            'value' => 'etsy',
        ],
        [
            'title' => 'Lazada',
            'value' => 'lazada',
        ]
        ];
    }
}
