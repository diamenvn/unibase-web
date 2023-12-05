<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\OrderActivityService;
use App\Services\OrderListService;
use App\Services\SettingService;
use App\Services\CustomerService;
use App\Helpers\App;

class SiteCustomerController extends Controller
{
    public function __construct(UserService $user, OrderActivityService $orderActivity, OrderListService $orderList, SettingService $setting, CustomerService $customerService)
    {
        $this->user = $user;
        $this->activity = $orderActivity;
        $this->order = $orderList;
        $this->setting = $setting;
        $this->customerService = $customerService;
    }

    public function create()
    {
        $info = $this->user->info()
        ->load('product')
        ->load('company')
        ->load('source');
        $typeAccount = array('mkt' => 'Marketing', 'sale' => 'Sale', 'bill' => 'Vận đơn', 'hcns' => 'Hành chính nhân sự','care' => 'Chăm sóc khách hàng');
        return view('site.customer.create')
        ->with('info', $info)
        ->with('typeAccount', $typeAccount);
    }

    public function list()
    {
        $user = $this->user->info();
        $activity = $this->getActivityByCustomer($user);
        $newCustomer = $this->order->getNewDataCustomerLimitNumber(10);
        $data['route_list'] = route('api.customer.getListCustomer');
        $data['tabs'] = $this->getListTabs();
        return view('site.customer.list', $data);
    }

    public function detail($id)
    {
        $user = $this->user->info();

        $customer = $this->customerService->first($id)->load('activity');
        
        if (empty($customer)) {
          return abort(404);
        }
    
        $urlBack = (string) url()->previous();
      
    
        return view('site.customer.detail')
          ->with('urlBack', $urlBack)
          ->with('info', $user)
          ->with('customer', $customer);
    }


    public function dashboard()
    {
        $user = $this->user->info();
        $activity = $this->getActivityByCustomer($user);
        $newCustomer = $this->order->getNewDataCustomerLimitNumber(10);

        return view('site.home.dashboard')
            ->with('customer', $user)
            ->with('activity', $activity)
            ->with('newCustomer', $newCustomer);
    }

    public function getActivityByCustomer($user)
    {
        $activity = [];
        $limit = 10;
        if ($user->permission == "super" || $user->permission == "admin") {
            $activity = $this->activity->getLastUpdateLimitNumber($limit)->load('customer');
        } elseif ($user->type_account == "mkt" || $user->type_account == "sale") {
            $activity = $user->load('activity')->activity->take($limit);
        }
        return $activity;
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
