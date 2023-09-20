<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use App\Services\OrderActivityService;
use App\Services\OrderListService;
use App\Services\SettingService;
use App\Helpers\App;

class SiteUserController extends Controller
{
    public function __construct(CustomerService $customer, OrderActivityService $orderActivity, OrderListService $orderList, SettingService $setting)
    {
        $this->customer = $customer;
        $this->activity = $orderActivity;
        $this->order = $orderList;
        $this->setting = $setting;
    }

    public function create()
    {
        $customer = $this->customer->info();
        
        if ($this->customer->isAdmin()) {
            $users = $this->customer->getAllByCompanyId($customer->company_id);
        }else{
            $group = $this->setting->findGroupByCustomerId($customer->_id);
            if (!empty($group)) {
                $members = $group->pluck('members')->toArray();
                $users = App::arrayMerge($members);
                $users = $this->customer->getById($users);
            }else{
                $users = [];
            }
        }
        $typeAccount = array('mkt' => 'Marketing', 'sale' => 'Sale', 'bill' => 'Vận đơn', 'hcns' => 'Hành chính nhân sự','care' => 'Chăm sóc khách hàng');
        return view('site.user.create')
        ->with('customer', $customer)
        ->with('typeAccount', $typeAccount)
        ->with('users', $users);
    }


    public function dashboard()
    {
        $customer = $this->customer->info();
        $activity = $this->getActivityByCustomer($customer);
        $newCustomer = $this->order->getNewDataCustomerLimitNumber(10);

        return view('site.home.dashboard')
            ->with('customer', $customer)
            ->with('activity', $activity)
            ->with('newCustomer', $newCustomer);
    }

    public function getActivityByCustomer($customer)
    {
        $activity = [];
        $limit = 10;
        if ($customer->permission == "super" || $customer->permission == "admin") {
            $activity = $this->activity->getLastUpdateLimitNumber($limit)->load('customer');
        } elseif ($customer->type_account == "mkt" || $customer->type_account == "sale") {
            $activity = $customer->load('activity')->activity->take($limit);
        }
        return $activity;
    }
}
