<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\OrderActivityService;
use App\Services\OrderListService;
use App\Services\SettingService;
use App\Helpers\App;

class SiteUserController extends Controller
{
    public function __construct(UserService $user, OrderActivityService $orderActivity, OrderListService $orderList, SettingService $setting)
    {
        $this->user = $user;
        $this->activity = $orderActivity;
        $this->order = $orderList;
        $this->setting = $setting;
    }

    public function list()
    {
        $user = $this->user->info();
        
        if ($this->user->isAdmin()) {
            $users = $this->user->getAllByCompanyId($user->company_id);
        }else{
            $group = $this->setting->findGroupByCustomerId($user->_id);
            if (!empty($group)) {
                $members = $group->pluck('members')->toArray();
                $users = App::arrayMerge($members);
                $users = $this->user->getById($users);
            }else{
                $users = [];
            }
        }
        $typeAccount = array('mkt' => 'Marketing', 'sale' => 'Sale', 'bill' => 'Vận đơn', 'hcns' => 'Hành chính nhân sự','care' => 'Chăm sóc khách hàng');
        return view('site.user.create')
        ->with('customer', $user)
        ->with('typeAccount', $typeAccount)
        ->with('users', $users);
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
}
