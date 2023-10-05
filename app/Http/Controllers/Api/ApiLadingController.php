<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\ListProvinService;
use App\Services\OrderActivityService;
use App\Services\OrderListService;
use App\Services\OrderShipService;
use App\Services\SettingService;
use App\Http\Requests\UpdateStatusRequest;
use App\Services\OrderCareService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ApiLadingController extends Controller
{
  public function __construct(OrderListService $orderList, OrderCareService $orderCareService, UserService $user, OrderActivityService $activity, OrderShipService $ship, ListProvinService $provin, SettingService $setting)
  {
    $this->order = $orderList;
    $this->orderCareService = $orderCareService;
    $this->activity = $activity;
    $this->user = $user;
    $this->ship = $ship;
    $this->provin = $provin;
    $this->setting = $setting;
    $this->response['msg'] = "Error!";
    $this->response['success'] = false;
    $this->response['data'] = [];
    $this->timeNow = Carbon::now();
  }

  public function getAllListLading(Request $request)
  {
    $user = $this->user->info();
    $request = $this->acceptRequest($request);
    $request = $this->defaultRequest($request);
    $request = $this->filterByPermissionCustomer($request);
    $products = [];
    $request['user_create_id'] = null;
    $total = 0;

    $data = collect();

    $userCreateId = $user->load('customer')->customer;

    if ($userCreateId) {
      $request['user_create_id'] = $userCreateId->pluck('_id')->toArray();
    }

    $orderModelName = 'order';

    $lists = $this->ship->search($request)
      ->with([$orderModelName => function ($q) use ($request) {
        $q->where('reason', "success");
        if (!empty($request['filter_status'])) {
          $q->whereIn('filter_status', $request['filter_status']);
        }
        if (!empty($request['filter_ship'])) {
          $q->whereIn('filter_ship', $request['filter_ship']);
        }
        if (!empty($request['filter_confirm']) && !in_array("null", $request['filter_confirm'])) {
          $q->whereIn('filter_confirm', $request['filter_confirm']);
        } elseif (!empty($request['filter_confirm']) &&  in_array("null", $request['filter_confirm'])) {
          $q->whereNull('filter_confirm');
        }
        if (!empty($request['filter_date_by']) && $request['filter_date_by'] == "date_confirm") {
          $filterDateBy = "created_at";
          if (!empty($request['type_confirm_text']) && $request['type_confirm_text'] == "date_confirm") {
            $filterDateBy = "date_confirm";
          }

          if (!empty($request['time_start'])) {
            $time = Carbon::parse($request['time_start'] . " " . "00:00:00");

            $q->where($filterDateBy, '>=', $time);
          }
          if (!empty($request['time_end'])) {
            $time = Carbon::parse($request['time_end'] . " " . "23:59:59");
            $q->where($filterDateBy, '<=', $time);
          }
        }

        $q
          ->with('product')
          ->with('reciver')
          ->with('source')
          ->with('filterConfirm')
          ->with('activityLanding');
      }])->get();
    // dd($lists);
    foreach ($lists as $value) {
      if (!empty($value->order) && $value->order->reason == "success") {
        $data->push($value);
        $total += (int)$value->total;
      }
    }

    $lists = $data->paginate($request['limit'])->setPath('');
    $products = $this->order->getByStatusActive()->keyBy('_id');

    $lists = array(
      'result'       => $lists,
      'product'      => $products,
      'total'       => $total,
      'pagination' => (string) $lists->links()
    );
    if ($lists) {
      $this->response['success'] = true;
      $this->response['msg'] = 'Lấy dữ liệu thành công';
      $this->response['data'] = $lists;
    }

    return response()->json($this->response, 200);
  }

  public function getAllListLadingCare(Request $request)
  {
    $user = $this->user->info();
    $request = $this->acceptRequest($request);
    $request = $this->defaultRequest($request);
    $request = $this->filterByPermissionCustomer($request);
    $products = [];
    $request['user_create_id'] = null;
    $total = 0;

    $data = collect();

    $userCreateId = $user->load('customer')->customer;

    if ($userCreateId) {
      $request['user_create_id'] = $userCreateId->pluck('_id')->toArray();
    }

    $lists = $this->ship->search($request)
      ->with(['orderCare' => function ($q) use ($request) {
        $q->where('reason', "success");
        if (!empty($request['filter_status'])) {
          $q->whereIn('filter_status', $request['filter_status']);
        }
        if (!empty($request['filter_ship'])) {
          $q->whereIn('filter_ship', $request['filter_ship']);
        }
        if (!empty($request['filter_confirm']) && !in_array("null", $request['filter_confirm'])) {
          $q->whereIn('filter_confirm', $request['filter_confirm']);
        } elseif (!empty($request['filter_confirm']) &&  in_array("null", $request['filter_confirm'])) {
          $q->whereNull('filter_confirm');
        }
        if (!empty($request['filter_date_by']) && $request['filter_date_by'] == "date_confirm") {
          $filterDateBy = "created_at";
          if (!empty($request['type_confirm_text']) && $request['type_confirm_text'] == "date_confirm") {
            $filterDateBy = "date_confirm";
          }

          if (!empty($request['time_start'])) {
            $time = Carbon::parse($request['time_start'] . " " . "00:00:00");

            $q->where($filterDateBy, '>=', $time);
          }
          if (!empty($request['time_end'])) {
            $time = Carbon::parse($request['time_end'] . " " . "23:59:59");
            $q->where($filterDateBy, '<=', $time);
          }
        }

        $q
          ->with('product')
          ->with('care')
          ->with('source')
          ->with('filterConfirm')
          ->with('activityLanding');
      }])->get();

    foreach ($lists as $value) {
      if (!empty($value->orderCare) && $value->orderCare->reason == "success") {
        $value->order = $value->orderCare;
        $data->push($value);
        $total += (int)$value->total;
      }
    }

    $lists = $data->paginate($request['limit'])->setPath('');
    $products = $this->order->getByStatusActive()->keyBy('_id');

    $lists = array(
      'result'       => $lists,
      'product'      => $products,
      'total'       => $total,
      'pagination' => (string) $lists->links()
    );
    if ($lists) {
      $this->response['success'] = true;
      $this->response['msg'] = 'Lấy dữ liệu thành công';
      $this->response['data'] = $lists;
    }

    return response()->json($this->response, 200);
  }

  public function updateStatus(UpdateStatusRequest $request)
  {
    $request = $request->only('form', 'order_id');
    $orderId = $request['order_id'];
    $data = [];
    $user = $this->user->info()->load('settingOrder');

    foreach ($request['form'] as $value) {
      $data[$value['name']] = $value['value'];
      if ($value['name'] == "filter_confirm") {
        $data['filter_status'] = $value['value'];
        $confirm = $this->ship->firstAction($value['value']);

        if (!empty($confirm) && !empty($confirm->text)) {
          $data['type_confirm_text'] = $confirm->text;
          $data['date_confirm'] = $this->timeNow;
          foreach ($orderId as $id) {
            $this->createActivityLog($id,  $confirm->text);
          }
        }

        if (isset($user->settingOrder->filter_confirm_care) && $user->settingOrder->filter_confirm_care == $value['value']) {
          foreach ($orderId as $key => $value) {
            $order = $this->order->firstById($value)->load('orderCare')->toArray();
            if (isset($order['order_care']) && !empty($order['order_care'])) continue;
            $order['order_id'] = $value;
            $order['is_order_care'] = true;
            unset($order['date_reciver']);
            unset($order['_id']);
            unset($order['filter_status']);
            unset($order['reason']);
            unset($order['filter_confirm']);
            $this->orderCareService->create($order);
          }
        }
      }
    }

    $res = $this->order->updateByOrderIdArray($orderId, $data);
    if ($res) {
      $this->response['success'] = true;
      $this->response['msg'] = "Cập nhật trạng thái thành công!";
    }
    return response()->json($this->response, 200);
  }

  public function updateStatusOrderCare(UpdateStatusRequest $request)
  {
    $request = $request->only('form', 'order_id');
    $orderId = $request['order_id'];
    $data = [];
    $user = $this->user->info()->load('settingOrder');

    foreach ($request['form'] as $value) {
      $data[$value['name']] = $value['value'];
      if ($value['name'] == "filter_confirm") {
        $data['filter_status'] = $value['value'];
        $confirm = $this->ship->firstAction($value['value']);

        if (!empty($confirm) && !empty($confirm->text)) {
          $data['type_confirm_text'] = $confirm->text;
          $data['date_confirm'] = $this->timeNow;
          foreach ($orderId as $id) {
            $this->createActivityLog($id,  $confirm->text);
          }
        }
      }
    }
    $res = $this->orderCareService->updateByOrderIdArray($orderId, $data);
    if ($res) {
      $this->response['success'] = true;
      $this->response['msg'] = "Cập nhật trạng thái thành công!";
    }
    return response()->json($this->response, 200);
  }

  public function createActivityLog($orderId, $text)
  {
    $user = $this->user->info();


    $log['note'] = 'Tài khoản vận đơn ' . $user->username . ' đã cập nhật trạng thái.';
    $log['note'] .= '<br><span class="co-green bold">Ghi chú: ' .  $text . '</span>';
    $log['ship_note'] = $text;
    $log['ship'] = 1;

    $log['origin_note'] = $text;
    $log['user_create_id'] = $user->_id;
    $log['order_id'] = $orderId;
    $log['status'] = 1;

    $this->activity->create($log);
  }


  public function getMembersGroupByCustomer($user)
  {
    // $group = $this->setting->findGroupByCustomerId($user->_id)->pluck('members');
    $group = collect();
    $group->push(array($user->_id));
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

  public function filterByPermissionCustomer($request)
  {
    $user = $this->user->info();
    $company = $user->load('company')->company;
    if ($company->company_type == "mkt" || ($company->company_type == "sale" && ($company->divideOrder == 1 || empty($company->divideOrder)))) {
      $request = $this->filterByDivideOrder($user, $request);
      $request['divideOrder'] = 1;
    } else {
      $request = $this->filterByAutoGetOrder($user, $request);
      $request['divideOrder'] = 0;
    }

    return $request;
  }

  public function filterByDivideOrder($user, $request)
  {
    if ($this->user->isAdminMkt()) {
      $request['company_id'] = $user->company_id;
    } elseif ($this->user->isAdminSale() || $this->user->isVandon()) {
      $connect = $user->load('companySale')->companySale;
      $request['product_id'] = $connect->pluck('product_id')->toArray();
      $request['company_id'] = $connect->pluck('company_mkt_id')->toArray();
    } elseif ($this->user->isMkt()) {
      $filter = $this->getMembersGroupByCustomer($user);
      $request['user_create_id'] = $filter;
    } elseif ($this->user->isSale()) {
      $filter = $this->getMembersGroupByCustomer($user);
      $request['user_reciver_id'] = $filter;
    }

    if ($this->user->isSale()) {
      if (!empty($request['user_id'])) {
        $request['user_reciver_id'] = $request['user_id'];
      }
    } else {
      if (!empty($request['user_id'])) {
        $request['user_create_id'] = $request['user_id'];
      }
    }

    return $request;
  }

  public function filterByAutoGetOrder($user, $request)
  {
    if ($this->user->isAdminMkt()) {
      $request['company_id'] = $user->company_id;
    } elseif ($this->user->isAdminSale() || $this->user->isVandon()) {
      $connect = $user->load('companySale')->companySale;
      $request['product_id'] = $connect->pluck('product_id')->toArray();
      $request['company_id'] = $connect->pluck('company_mkt_id')->toArray();
    } elseif ($this->user->isMkt()) {
      $filter = $this->getMembersGroupByCustomer($user);
      $request['user_create_id'] = $filter;
    } elseif ($this->user->isSale()) {
      $connect = $user->load('companySale')->companySale;
      $request['product_id'] = $connect->pluck('product_id')->toArray();
      $request['company_id'] = $connect->pluck('company_mkt_id')->toArray();
      $request['date_reciver'] = null;
      $request['user_reciver_id'] = $user->_id;
    }

    if ($this->user->isSale()) {
      if (!empty($request['user_id'])) {
        $request['user_reciver_id'] = $request['user_id'];
      }
    } else {
      if (!empty($request['user_id'])) {
        $request['user_create_id'] = $request['user_id'];
      }
    }

    return $request;
  }

  public function defaultRequest($request)
  {
    $request['limit'] = !empty($request['limit']) ? (int) $request['limit'] : 20;
    if ($this->user->isVandon() && $this->user->isUser()) {
      $request['reason'] = "success";
    }

    $request['filter_date_by'] = !empty($request['filter_date_by']) ? $request['filter_date_by'] : 'created_at';

    return $request;
  }


  public function acceptRequest($request)
  {
    return $request->only(
      'search',
      'name',
      'phone',
      'address',
      'product_id',
      'order_id',
      'company_mkt_id',
      'source_id',
      'user_id',
      'user_create_id',
      'user_reciver_id',
      'link',
      'reason',
      'filter_status',
      'filter_ship',
      'filter_confirm',
      'note',
      'message',
      'ship-name',
      'ship-phone',
      'ship-provin',
      'ship-district',
      'ship-town',
      'ship-address',
      'ship-product',
      'ship-amount',
      'ship-price',
      'ship-note_ship',
      'ship-note_delivery',
      'ship-discount',
      'ship-transport',
      'ship-charge',
      'ship-total',
      'time_start',
      'time_end',
      'created_at',
      'updated_at',
      'type_confirm_text',
      'filter_date_by',
      'limit',
      ''
    );
  }
}
