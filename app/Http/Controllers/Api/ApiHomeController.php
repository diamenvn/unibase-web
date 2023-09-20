<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ActionActivityService;
use App\Services\CustomerService;
use App\Services\ListProvinService;
use App\Services\OrderActivityService;
use App\Services\OrderListService;
use App\Services\OrderShipService;
use App\Services\SettingService;
use Carbon\Carbon;

class ApiHomeController extends Controller
{
    public function __construct(OrderListService $orderList, CustomerService $customer, OrderActivityService $activity, OrderShipService $ship, ListProvinService $provin, SettingService $setting, ActionActivityService $action)
    {
        $this->order = $orderList;
        $this->activity = $activity;
        $this->customer = $customer;
        $this->ship = $ship;
        $this->action = $action;
        $this->provin = $provin;
        $this->setting = $setting;
        $this->response['msg'] = "Error!";
        $this->response['success'] = false;
        $this->response['data'] = [];
        $this->timeNow = Carbon::now();
    }

    public function reportHome()
    {
        $customer = $this->customer->info();
        $data = [];
        $start = Carbon::parse(date('Y-m-d') . " " . "00:00:00");
        $end = Carbon::parse(date('Y-m-d') . " " . "23:59:59");

        $startMonth = Carbon::now()->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();

        $orderSuccessToday = $orderConfirmToday = $orderReturnToday = $orderSuccessMonth = $orderConfirmMonth = $orderReturnMonth = 0;

        $action = $this->action->get(['type' => 'filter_confirm'])->keyBy('type_confirm');
        $idActionReturn = $action[4]->_id;

        if ($this->customer->isMkt()) {
            $order = $this->order->getByUserCreateId($customer);
        } else {
            $order = $this->order->getByUserReciverId($customer);
        }
        $orderToday = $orderMonth = $order;
        
        $data['orderNew'] = $order->whereNull('reason')->count();
        $data['orderSuccess'] = $order->where('reason', 'success')->count();
        $data['orderWait'] = $order->where('reason', 'wait')->count();

        $orderMonth->load(['ship' => function ($q) use ($startMonth, $endMonth) {
            $q->where('date_success_order', '>=', $startMonth)->where('date_success_order', '<=', $endMonth)->where('reason', 'success');
        }]);
        
        foreach ($orderMonth as $value) {
            if (!empty($value->ship)) {
                $orderSuccessMonth += $value->ship->total;
                if (!empty($value->filter_confirm) && $value->filter_confirm != $idActionReturn) {
                    $orderConfirmMonth += $value->ship->total;
                }
                if (!empty($value->filter_confirm) && $value->filter_confirm == $idActionReturn) {
                    $orderReturnMonth += $value->ship->total;
                }
            }
        }
        $orderToday->load(['ship' => function ($q) use ($start, $end) {
            $q->where('date_success_order', '>=', $start)->where('date_success_order', '<=', $end)->where('reason', 'success');
        }]);
        foreach ($orderToday as $value) {
            if (!empty($value->ship)) {
                $orderSuccessToday += $value->ship->total;
                if (!empty($value->filter_confirm) && $value->filter_confirm != $idActionReturn) {
                    $orderConfirmToday += $value->ship->total;
                }
                if (!empty($value->filter_confirm) && $value->filter_confirm == $idActionReturn) {
                    $orderReturnToday += $value->ship->total;
                }
            }
        }
        
        $data['orderSuccessToday'] = $orderSuccessToday;
        $data['orderConfirmToday'] = $orderConfirmToday;
        $data['orderReturnToday'] = $orderReturnToday;

        $data['orderSuccessMonth'] = $orderSuccessMonth;
        $data['orderConfirmMonth'] = $orderConfirmMonth;
        $data['orderReturnMonth'] = $orderReturnMonth;

        $this->response['success'] = true;
        $this->response['data'] = $data;
        $this->response['msg'] = "Thành công";
        
        return response()->json($this->response, 200);
    }

    public function getMembersGroupByCustomer($customer)
    {
        // $group = $this->setting->findGroupByCustomerId($customer->_id)->pluck('members');
        $group = collect();
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
