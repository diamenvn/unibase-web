<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use App\Services\ListProvinService;
use App\Services\OrderActivityService;
use App\Services\OrderListService;
use App\Services\OrderShipService;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\LadingExport;
use App\Exports\OrderExport;
use Excel;

class ApiExportController extends Controller
{
    public function __construct(OrderListService $orderList, CustomerService $customer, OrderActivityService $activity, OrderShipService $ship, ListProvinService $provin, SettingService $setting)
    {
        $this->order = $orderList;
        $this->activity = $activity;
        $this->customer = $customer;
        $this->ship = $ship;
        $this->provin = $provin;
        $this->setting = $setting;
        $this->response['msg'] = "Error!";
        $this->response['success'] = false;
        $this->response['data'] = [];
        $this->timeNow = Carbon::now();
    }

    public function exportExcelLading(Request $request)
    {
        return Excel::download(new LadingExport(
            $this->ship,
            $this->order,
            $this->customer,
            $request
        ), 'Du_lieu_van_don.xlsx');
    }

    public function exportExcelOrder(Request $request)
    {
        return Excel::download(new OrderExport(
            $this->order,
            $this->customer,
            $request
        ), 'Du_lieu_don_hang.xlsx');
    }
}
