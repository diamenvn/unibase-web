<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ActionActivityService;
use App\Services\CustomerService;
use App\Services\OrderActivityService;
use App\Services\OrderListService;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use XSSCleaner;

class ApiReportController extends Controller
{
  public function __construct(OrderListService $orderList, CustomerService $customer, OrderActivityService $activity, ActionActivityService $action, SettingService $setting)
  {
    $this->order = $orderList;
    $this->activity = $activity;
    $this->customer = $customer;
    $this->action = $action;
    $this->setting = $setting;
    $this->response['msg'] = "Error!";
    $this->response['success'] = false;
    $this->response['data'] = [];
    $this->timeNow = Carbon::now();
  }

  public function getAllBilling(Request $request)
  {
    $request = $this->acceptRequest($request);
    $request = $this->defaultRequest($request);
    $request = $this->filterByPermissionCustomer($request);
    
    if ($this->customer->isMkt()) {
      return $this->reportBillingForMkt($request);
    } else {
      return $this->reportBillingForSale($request);
    }
  }

  public function reportBillingForMkt($request)
  {
    $customer = $this->customer->info();
    $reports = $this->customer->getReportAllBilling($customer, $request)->where('type_account', 'mkt');
    $action = $this->action->get(['type' => 'filter_confirm'])->keyBy('type_confirm');
    $idActionConfirm = $action[0]->_id;
    $idActionReturn = $action[4]->_id;

    $data = collect();

    foreach ($reports as $key => $value) {
      $totalSend = $totalReturn = $totalDone = 0;
      $order = $value->orderByMkt;
      if (isset($request['product_id']) && $request['product_id'] != '-1') {
        $order = $order->where('product_id', $request['product_id']);
      }

      if (isset($request['time_start'])) {
        $start = Carbon::parse($request['time_start'])->startOfDay();
        $order = $order->where('created_at', '>=', $start);
      }
      if (isset($request['time_end'])) {
        $end = Carbon::parse($request['time_end'])->endOfDay();
        $order = $order->where('created_at', '<=', $end);
      }

      $item['username'] = $value->username;
      $item['countPhone'] = count($order);
      $item['countOrder'] = count($order->where('reason', 'success'));

      $start = $value->orderByMkt;
      if (isset($request['product_id']) && $request['product_id'] != '-1') {
        $start = $start->where('product_id', $request['product_id']);
      }
      foreach ($start as $key => $total) {
        if (!empty($total->ship) && $total->filter_confirm == $idActionConfirm) {
          $totalSend += $total->ship->total;
        }
        if ($total->reason == "success" && !empty($total->ship)) {
          $totalDone += $total->ship->total;
        }
        
      }

      $end = $value->orderByMkt->where('filter_confirm', $idActionReturn);
      if (isset($request['product_id']) && $request['product_id'] != '-1') {
        $end = $end->where('product_id', $request['product_id']);
      }
      foreach ($end as $key => $total) {
        if (!empty($total->ship)) {
          $totalReturn += $total->ship->total;
        }
      }
      $item['totalDone'] = $totalDone;
      $item['totalSend'] = $totalSend;
      $item['totalSuccess'] = 0;
      $item['totalReturn'] = $totalReturn;
      $data->push($item);
    }

    $lists = array(
      'reports' => $data,
    );
    if ($lists) {
      $this->response['success'] = true;
      $this->response['msg'] = 'Lấy dữ liệu thành công';
      $this->response['data'] = $lists;
    }

    return response()->json($this->response, 200);
  }

  public function reportBillingForSale(Request $request)
  {
    $request = $this->acceptRequest($request);
    $request = $this->defaultRequest($request);
    $request = $this->filterByPermissionCustomer($request);


    $customer = $this->customer->info();
    $reports = $this->customer->getReportAllBilling($customer, $request)->where('type_account', 'sale');
    $action = $this->action->get(['type' => 'filter_confirm'])->keyBy('type_confirm');
    $idActionConfirm = $action[0]->_id;
    $idActionReturn = $action[4]->_id;

    $data = collect();

    foreach ($reports as $key => $value) {
      $totalSend = $totalReturn = $totalDone = 0;
      $order = $value->orderBySale;
      if (isset($request['product_id']) && $request['product_id'] != '-1') {
        $order = $order->where('product_id', $request['product_id']);
      }

      if (isset($request['time_start'])) {
        $start = Carbon::parse($request['time_start'])->startOfDay();
        $order = $order->where('date_reciver', '>=', $start);
      }
      if (isset($request['time_end'])) {
        $end = Carbon::parse($request['time_end'])->endOfDay();
        $order = $order->where('date_reciver', '<=', $end);
      }

      $item['username'] = $value->username;
      $item['countPhone'] = count($order);
      $item['countOrder'] = count($order->where('reason', 'success'));

      $start = $value->orderBySale;
      if (isset($request['product_id']) && $request['product_id'] != '-1') {
        $start = $start->where('product_id', $request['product_id']);
      }
      foreach ($start as $key => $total) {
        if (!empty($total->ship) && $total->filter_confirm == $idActionConfirm) {
          $totalSend += $total->ship->total;
        }
        if ($total->reason == "success" && !empty($total->ship)) {
          $totalDone += $total->ship->total;
        }
        
      }

      $end = $value->orderBySale->where('filter_confirm', $idActionReturn);
      if (isset($request['product_id']) && $request['product_id'] != '-1') {
        $end = $end->where('product_id', $request['product_id']);
      }
      foreach ($end as $key => $total) {
        if (!empty($total->ship)) {
          $totalReturn += $total->ship->total;
        }
      }
      $item['totalDone'] = $totalDone;
      $item['totalSend'] = $totalSend;
      $item['totalSuccess'] = 0;
      $item['totalReturn'] = $totalReturn;
      $data->push($item);
    }

    $lists = array(
      'reports' => $data,
    );
    if ($lists) {
      $this->response['success'] = true;
      $this->response['msg'] = 'Lấy dữ liệu thành công';
      $this->response['data'] = $lists;
    }

    return response()->json($this->response, 200);
  }

  public function reportBillingForSaleFromMkt(Request $request)
  {
    $customer = $this->customer->info();
    $companyId = $this->arrayUnique($customer->load('companyMkt')->companyMkt->pluck('company_sale_id')->toArray());

    $reports = $this->customer->getReportAllBillingSaleFromMkt($companyId, $request);

    $action = $this->action->get(['type' => 'filter_confirm'])->keyBy('type_confirm');
    $idActionConfirm = $action[0]->_id;
    $idActionReturn = $action[4]->_id;

    $data = collect();

    foreach ($reports as $key => $value) {
      $totalSend = $totalReturn = $totalDone = 0;
      $order = $value->orderBySale;
      if (isset($request['product_id']) && $request['product_id'] != '-1') {
        $order = $order->where('product_id', $request['product_id']);
      }

      if (isset($request['time_start'])) {
        $start = Carbon::parse($request['time_start'])->startOfDay();
        $order = $order->where('date_reciver', '>=', $start);
      }
      if (isset($request['time_end'])) {
        $end = Carbon::parse($request['time_end'])->endOfDay();
        $order = $order->where('date_reciver', '<=', $end);
      }

      $item['username'] = $value->username;
      $item['countPhone'] = count($order);
      $item['countOrder'] = count($order->where('reason', 'success'));
      $item['totalDone'] = count($order->where('reason', 'success'));
      

      $start = $value->orderBySale;
      if (isset($request['product_id']) && $request['product_id'] != '-1') {
        $start = $start->where('product_id', $request['product_id']);
      }
      
      foreach ($start as $key => $total) {
        if (!empty($total->ship) && $total->filter_confirm == $idActionConfirm) {
          $totalSend += $total->ship->total;
        }
        if ($total->reason == "success" && !empty($total->ship)) {
          $totalDone += $total->ship->total;
        }
        
      }
      

      $end = $value->orderBySale->where('filter_confirm', $idActionReturn);
      if (isset($request['product_id']) && $request['product_id'] != '-1') {
        $end = $end->where('product_id', $request['product_id']);
      }
      foreach ($end as $key => $total) {
        if (!empty($total->ship)) {
          $totalReturn += $total->ship->total;
        }
      }
      $item['totalDone'] = $totalDone;
      $item['totalSend'] = $totalSend;
      $item['totalSuccess'] = 0;
      $item['totalReturn'] = $totalReturn;
      $data->push($item);
    }

    $lists = array(
      'reports' => $data,
    );
    if ($lists) {
      $this->response['success'] = true;
      $this->response['msg'] = 'Lấy dữ liệu thành công';
      $this->response['data'] = $lists;
    }

    return response()->json($this->response, 200);
  }


  public function getForPersonal(Request $request)
  {
    $request = $this->acceptRequest($request);
    $request = $this->defaultRequest($request);
    $request = $this->filterByPermissionCustomer($request);

    if ($this->customer->isMkt()) {
      return $this->reportPersonalForMkt($request);
    } else {
      return $this->reportPersonalForSale($request);
    }
  }

  public function reportPersonalForMkt($request)
  {
    $customer = $this->customer->info();
    $month = $request['month'];

    $order = $this->order->getInMonthForMkt($customer, $month, $request)->load('ship');
    $endOfMonth = $this->findLastDayInTheMonth($month);
    $action = $this->action->get(['type' => 'filter_confirm'])->keyBy('type_confirm');
    $idActionConfirm = $action[0]->_id;
    $idActionReturn = $action[4]->_id;
    $idActionSuccess = $action[5]->_id;

    $data = [];
    if ($month < 10 && strlen($month) == 1) $month = 0 . $month;
    for ($i = 1; $i <= $endOfMonth; $i++) {
      if ($i < 10) $day = 0 . $i;
      else $day = $i;
      $item = [];
      $start = Carbon::parse(date('Y') . '-' . $month . '-' . $day)->startOfDay();
      $end = Carbon::parse(date('Y') . '-' . $month . '-' . $day)->endOfDay();

      $item['countPhone'] = $item['countOrder'] = $item['totalSend'] = $item['totalReturn'] = $item['totalSuccess'] = $item['totalDone'] = 0;

      foreach ($order as $value) {
        $shipInDay = $orderInDay = null;
        $orderInDay = $value->created_at->greaterThanOrEqualTo($start) && $value->created_at->lessThanOrEqualTo($end);
        if ($orderInDay) {
          $item['countPhone'] += 1;
        }

        if (!empty($value->ship)) {
          $shipInDay = $value->ship->created_at->greaterThanOrEqualTo($start) && $value->ship->created_at->lessThanOrEqualTo($end);
          if ($shipInDay && $value->reason == "success") {
            $item['countOrder'] += 1;
            $item['totalDone'] += $value->ship->total;
          }
        }

        if (!empty($value->filter_confirm) && $value->filter_confirm == $idActionConfirm && $shipInDay) {
          $item['totalSend'] += $value->ship->total;
        }

        if (!empty($value->filter_confirm) && $value->filter_confirm == $idActionReturn && $shipInDay) {
          $item['totalReturn'] += $value->ship->total;
        }

        if (!empty($value->filter_confirm) && $value->filter_confirm == $idActionSuccess && $shipInDay) {
          $item['totalSuccess'] += $value->ship->total;
        }
      }

      $data[$day . '-' . $month . '-' . date('Y')] = $item;
    }
    $lists = array(
      'reports' => $data,
    );

    if ($data) {
      $this->response['success'] = true;
      $this->response['msg'] = 'Lấy dữ liệu thành công';
      $this->response['data'] = $lists;
    }

    return response()->json($this->response, 200);
  }

  public function reportPersonalForSale($request)
  {
    $customer = $this->customer->info();
    $month = $request['month'];

    $order = $this->order->getInMonthForSale($customer, $month, $request)->load('ship');
    $endOfMonth = $this->findLastDayInTheMonth($month);
    $action = $this->action->get(['type' => 'filter_confirm'])->keyBy('type_confirm');
    $idActionConfirm = $action[0]->_id;
    // $idActionSend = $action[2]->_id;
    $idActionReturn = $action[4]->_id;
    $idActionSuccess = $action[5]->_id;

    $data = [];
    if ($month < 10 && strlen($month) == 1) $month = 0 . $month;
    for ($i = 1; $i <= $endOfMonth; $i++) {
      if ($i < 10) $day = 0 . $i;
      else $day = $i;
      $item = [];
      $start = Carbon::parse(date('Y') . '-' . $month . '-' . $day)->startOfDay();
      $end = Carbon::parse(date('Y') . '-' . $month . '-' . $day)->endOfDay();

      $item['countPhone'] = $item['countOrder'] = $item['totalSend'] = $item['totalReturn'] = $item['totalSuccess'] = $item['totalDone'] = 0;

      foreach ($order as $value) {
        $shipInDay = $orderInDay = null;
        $orderInDay = $value->date_reciver->greaterThanOrEqualTo($start) && $value->date_reciver->lessThanOrEqualTo($end);
        if ($orderInDay) {
          $item['countPhone'] += 1;
        }

        if (!empty($value->ship)) {
          $shipInDay = $value->ship->created_at->greaterThanOrEqualTo($start) && $value->ship->created_at->lessThanOrEqualTo($end);
          if ($shipInDay && $value->reason == "success") {
            $item['countOrder'] += 1;
            $item['totalDone'] += $value->ship->total;
          }
        }

        if (!empty($value->filter_confirm) && $value->filter_confirm == $idActionConfirm && $shipInDay) {
          $item['totalSend'] += $value->ship->total;
        }

        if (!empty($value->filter_confirm) && $value->filter_confirm == $idActionReturn && $shipInDay) {
          $item['totalReturn'] += $value->ship->total;
        }

        if (!empty($value->filter_confirm) && $value->filter_confirm == $idActionSuccess && $shipInDay) {
          $item['totalSuccess'] += $value->ship->total;
        }
      }

      $data[$day . '-' . $month . '-' . date('Y')] = $item;
    }
    $lists = array(
      'reports' => $data,
    );

    if ($data) {
      $this->response['success'] = true;
      $this->response['msg'] = 'Lấy dữ liệu thành công';
      $this->response['data'] = $lists;
    }

    return response()->json($this->response, 200);
  }

  public function note(Request $request)
  {
    $customer = $this->customer->info();
    $request['limit'] = isset($request['limit']) ? $request['limit'] : 20;
    $listArrayCompanyId = [];
  
    if ($this->customer->isAdmin() && empty($request['user_create_id'])) {
      array_push($listArrayCompanyId, $customer->company_id);

      if ($this->customer->isMkt()) {
        $company = $this->arrayUnique($customer->load('companyMkt')->companyMkt->pluck('company_sale_id')->toArray());
      } else {
        $company = $this->arrayUnique($customer->load('companySale')->companyMkt->pluck('company_mkt_id')->toArray());
      }

      foreach ($company as $value) {
        array_push($listArrayCompanyId, $value);
      }
      $listUsers = $this->customer->getAllByCompanyId($listArrayCompanyId)->pluck('_id')->toArray();
    } else {
      $listUsers = $this->getMembersGroupByCustomer($customer);
    }
    
    if (!empty($request['user_create_id'])) {
      $listUsers = [];
      $listUsers[] = $request['user_create_id'];
    }
    

    
    $activityOrder = $this->activity->getByUserId($listUsers);
    if (!empty($request['reason']) && $request['reason'] != -1) {
      $activityOrder = $activityOrder->where('reason', $request['reason']);
    }
    if (!empty($request['time_start']) && $request['time_start'] != -1) {
      $start = Carbon::parse($request['time_start'])->startOfDay();
      $activityOrder = $activityOrder->where('created_at', '>=' , $start);
    }
    if (!empty($request['time_end']) && $request['time_end'] != -1) {
      $end = Carbon::parse($request['time_end'])->endOfDay();
      $activityOrder = $activityOrder->where('created_at', '<=', $end);
    }
    $activityOrder = $activityOrder->load('order')->load('orderCare')->load('customer');
  
    $data = collect();

    foreach ($activityOrder as $value) {
      $data->push($value);
    }


    $lists = $data->paginate($request['limit'])->setPath('');
    $products = $this->order->getByStatusActive()->keyBy('_id');

    $lists = array(
      'result'       => $lists,
      'product'      => $products,
      'pagination' => (string) $lists->links()
    );
    if ($lists) {
      $this->response['success'] = true;
      $this->response['msg'] = 'Lấy dữ liệu thành công';
      $this->response['data'] = $lists;
    }

    return response()->json($this->response, 200);
  }

  public function findLastDayInTheMonth($month)
  {
    $year = date('Y');
    $dateTime = $year . '-' . $month . '-' . 01;
    $endOfMonth = Carbon::parse($dateTime)->endOfMonth();
    return $endOfMonth->day;
  }

  public function getCustomerFromArrayId($array)
  {
    if (!empty($array)) {
      $array = $this->arrayMerge($array);
      return $this->customer->get(['_id' => $array]);
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

  public function arrayUnique($array)
  {
    if (!empty($array)) {
      return array_unique($array);
    } else {
      return [];
    }
  }

  public function filterByPermissionCustomer($request)
  {
    $customer = $this->customer->info();
    return $request;
  }

  public function defaultRequest($request)
  {
    $request['month'] = isset($request['month']) ? $request['month'] : date('m');
    return $request;
  }

  public function acceptRequest($request)
  {
    return $request->only(
      'search',
      'product_id',
      'time_start',
      'time_end',
      'month'
    );
  }
}
