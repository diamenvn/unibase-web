<?php

namespace App\Http\Controllers\Api;

use App\Helpers\App;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\RemoveOrderRequest;
use App\Http\Requests\SaveAssignRequest;
use App\Http\Requests\SaveOrderRequest;
use App\Http\Requests\SearchPhoneRequest;
use App\Services\CustomerService;
use App\Services\ListProvinService;
use App\Services\OrderActivityService;
use App\Services\OrderCareService;
use App\Services\OrderListService;
use App\Services\OrderShipService;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use XSSCleaner;

class ApiOrderController extends Controller
{
  public function __construct(
    OrderListService $orderList, 
    OrderCareService $orderCareService, 
    CustomerService $customer, 
    OrderActivityService $activity, 
    OrderShipService $ship, 
    ListProvinService $provin, 
    SettingService $setting
  )
  {
    $this->order = $orderList;
    $this->orderCareService = $orderCareService;
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

  public function getListLabel(Request $request)
  {
    $customer = $this->customer->info();
    $columns = $this->order->getListLabelByCompanyId($customer->company_id)->load('order');
    if ($columns) {
      $this->response['success'] = true;
      $this->response['msg'] = 'Lấy dữ liệu thành công';
      $this->response['data'] = view('site.order.components.grid-column-header')->with('columns', $columns)->render();
    }
    return response()->json($this->response, 200);
  }

  public function getAllListOrder(Request $request)
  {
    
    $customer = $this->customer->info()->load('settingOrder');
    $request = $this->acceptRequest($request);
    $request = $this->defaultRequest($request);

    $request = App::filterByPermissionCustomer($customer, $request);
    $request['label_ids'] = $this->order->getListLabelByCompanyId($customer->company_id)->pluck('_id')->toArray();
    $isPaginate = (isset($request['paginate']) && $request['paginate']);
    $lists = $this->order->search($request)
      ->select('name', 'phone', 'date_reciver', 'product_id', 'source_id', 'user_reciver_id','user_create_id', 'proccessing', 'reason', 'created_at', 'filter_status', 'label_id')
      ->with('product')
      ->with('source')
      ->with('reciver')
      ->with('care')
      ->with('customerCreateOrder')
      ->with('filterStatus')
      ->with('activity')
      ->with([
        'order' => function ($query) use ($request) {
          if (!empty($request['company_id'])) {
            if (is_array($request['company_id'])) {
              $query->whereIn('company_id', $request['company_id']);
            } else {
              $query->where('company_id', $request['company_id']);
            }
          }
        },
      ]);
    
    if ($isPaginate) {
      $lists = $lists->paginate($request['limit'])->setPath('');
    } else {
      $lists = $lists->get();
      $renderLists = array();

      foreach ($lists as $key => $order) {
        array_push($renderLists, array(
          'label_id' => $order->label_id, 
          'html' => view('site.order.components.grid-body-item')->with('order', $order)->render())
        );
      }
    }
    $results = array(
      'result'       => $isPaginate ? $lists : $renderLists,
      'pagination' => (isset($request['paginate']) && $request['paginate']) ? (string) $lists->links() : false
    );
    if ($lists) {
      $this->response['success'] = true;
      $this->response['msg'] = 'Lấy dữ liệu thành công';
      $this->response['data'] = $results;
    }

    return response()->json($this->response, 200);
  }

  public function getAllListMyOrder(Request $request)
  {
    $customer = $this->customer->info();
    $request = $this->defaultRequest($request);
    $search = null;
    $products = $this->order->getByStatusActive()->keyBy('_id');
    if (!empty($request['search'])) {
      $search = $request['search'];
      unset($request['search']);
    }
    $lists = $this->activity->queryByCustomerId($customer->_id, $request)
      ->select('order_id', 'user_create_id', 'origin_note', 'reason', 'product_id', 'updated_at')
      ->with('order.filterStatus')
      ->with(['order' => function ($q) use ($search) {
        if (!empty($search)) {
          $q->where(function ($q) use ($search) {
            $q->orWhere('name', 'like', '%' . $search . '%')->orWhere('phone', 'like', '%' . $search . '%');
          });
        }
      }])

      ->get();

    $data = collect();
    foreach ($lists as $value) {
      if (!empty($value->order)) {
        $data->push($value);
      }
    }
    $lists = $data->paginate($request['limit'])->setPath('');


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


  public function saveDetail($id, SaveOrderRequest $request)
  {
    $request = $this->acceptRequest($request);
    $order = $this->order->firstById($id) ?? $this->orderCareService->firstById($id);
    $dataOld = [];
    if (!empty($order)) {
      $dataOld = $order->toArray();

      $update = $this->updateOrder($order, $request);
      if (!empty($update)) {
        $this->createActivityLog($order, $request, $dataOld);
        $this->response['success'] = true;
        $this->response['msg'] = 'Cập nhật đơn hàng thành công!';
      }
    } else {
      $this->response['msg'] = 'Không tìm thấy đơn hàng này!';
    }
    return response()->json($this->response, 200);
  }

  public function saveOrder(CreateOrderRequest $request)
  {
    $request = $this->acceptRequest($request);
    $customer = $this->customer->info();
    if (!$this->customer->isMkt()) {
      $this->response['msg'] = "Chỉ Marketing mới có thể tạo đơn";
      return response()->json($this->response, 403);
    }
    if ($this->customer->isUserMkt()) {
      $request['user_create_id'] = $customer->_id;
    }

    $request['company_id'] = $customer->company_id;
    $request['status'] = 1;

    $res = $this->order->create($request);
    if (!empty($res)) {
      $this->response['success'] = true;
      $this->response['msg'] = "Đã tạo đơn thành công";
    } else {
      $this->response['msg'] = "Tạo đơn hàng thất bại";
    }
    return response()->json($this->response, 200);
  }

  public function saveAssign(SaveAssignRequest $request)
  {
    $request = $this->acceptRequest($request);
    $customer = $this->customer->info()->load('customer');

    // Validate permission
    if (!$this->customer->isAdminSale() && !$customer->assign_order) {
      $this->response['msg'] = "Bạn không có quyền gán đơn hàng";
      return response()->json($this->response, 200);
    }

    foreach ($request['order_id'] as $orderId) {
      $order = $this->order->firstById($orderId);
      if (!empty($order)) {
        $userId = $request['user_reciver_id'];
        // if (in_array($userId, $customerInCompanyArray)) {
        $data['user_reciver_id'] = $userId;
        // }
        $this->createActivityLogAssign($order, $userId);
        $this->order->update($order, $data);
      }
    }
    $this->response['success'] = true;
    $this->response['msg'] = "Đã gán đơn hàng thành công!";

    return response()->json($this->response, 200);
  }

  public function updateOrder($order, $request)
  {
    if ($this->customer->isMkt()) {
      $data['name'] = $request['name'];
      $data['phone'] = $request['phone'];
      $data['product_id'] = $request['product_id'];
      $data['source_id'] = $request['source_id'];
      $data['source_id'] = $request['source_id'];
      $data['link'] = $request['link'];
      $data['address'] = $request['address'];
      $data['filter_status'] = $request['filter_status'];

      if ($this->customer->isAdminMkt()) {
        $data['user_create_id'] = $request['user_create_id'];
      }
    } else {
      $data['reason'] = $request['reason'];
      if (empty($order->user_reciver_id)) {
        $data['user_reciver_id'] = $this->customer->info()->_id;
      }

      if($order->is_order_care && empty($order->user_care_id)) {
        $data['user_care_id'] = $this->customer->info()->_id;
      }
    }

    $data['filter_status'] = $request['filter_status'];
    $confirm = $this->ship->firstAction($request['filter_status']);

    if (!empty($confirm) && !empty($confirm->type_confirm_text)) {
      $data['type_confirm_text'] = $confirm->type_confirm_text;
      $data['date_confirm'] = $this->timeNow;
    }

    if ($this->customer->isSale()) {
      $data['proccessing'] = false;
    }
    return $this->order->update($order, $data);
  }

  public function removeOrder(RemoveOrderRequest $request)
  {
    $request = $request->only('_id');
    
    $order = $this->order->firstById($request['_id']);
    if ($order) {
      if (!empty($order->filter_confirm)) {
        $this->response['msg'] = 'Đơn đã xác nhận không thể chỉnh sửa';
        return response()->json($this->response, 200);
      }
      $res = $this->order->removeById($request);
      if ($res) {
        $data['order_id'] = $request['_id'];
        $this->activity->removeByOrderId($data);
        $this->response['success'] = true;
        $this->response['msg'] = "Đã xoá thành công!";
      }
    }else{
      $this->response['msg'] = "Đơn này không tồn tại";
    }
    
    return response()->json($this->response, 200);
  }

  public function createActivityLog($order, $request, $data)
  {
    $user = $this->customer->info();
    if ($this->customer->isVandon() && !$this->customer->isAdmin()) {
      $log['note'] = 'Tài khoản vận đơn ' . $user->username . ' đã cập nhật trạng thái.';
      if (!empty($request['note'])) {
        $log['note'] .= '<br><span class="co-green bold">Ghi chú: ' .  $request['note'] . '</span>';
      }
      $log['ship_note'] = $request['note'];
      $log['ship'] = 1;
    } else {
      $log['note'] = 'Tài khoản ' . $user->type_account . ' ' . $user->username . ' đã cập nhật đơn hàng. ';
      if (!empty($request['note'])) {
        $log['note'] .= '<br><span class="co-red bold">Ghi chú: ' .  $request['note'] . '</span>';
      }
    }

    // foreach ($request as $key => $value) {
    //   if (!empty($data[$key])) {
    //     if ($request[$key] !== $data[$key]) {
    //       $log['note'] .= '<br><span class="bold">-------------</span><br><span>Thay đổi: ' .  '<span class="bold">' . $key . '</span>' . ' từ ' . '<span class="bold">' . $data[$key] . '</span>' . ' sang ' . '<span class="bold">' . $request[$key] . '</span></span>';
    //     }
    //   }
    // }

    // if ($order->ship) {
    //   foreach ($request as $key => $value) {
    //     if (!empty($order->ship->$key)) {
    //       if ($request[$key] !== $order->ship->$key) {
    //         $log['note'] .= '<br><span class="bold">-------------</span><br><span>Thay đổi: ' .  '<span class="bold">' . $key . '</span>' . ' từ ' . '<span class="bold">' . $data[$key] . '</span>' . ' sang ' . '<span class="bold">' . $request[$key] . '</span></span>';
    //       }
    //     }
    //   }
    // }

    $log['origin_note'] = $request['note'];
    $log['user_create_id'] = $user->_id;
    $log['order_id'] = $order->_id;
    $log['product_id'] = $order->product_id;
    $log['source_id'] = $order->source_id;
    $log['reason'] = $request['reason'];
    $log['status'] = 1;

    $update['reason'] = $request['reason'];
    $res = $this->activity->updateByOrderId($order->_id, $update);
    $this->activity->create($log);
  }

  public function createActivityLogAssign($order, $userReciverId)
  {
    $user = $this->customer->info();
    $userReciver = $this->customer->first($userReciverId);

    $log['note'] = '<span class="co-purple bold">Ghi chú: Tài khoản ' . $user->username . ' đã gán đơn hàng cho sale ' . $userReciver->username . '</span>';

    $log['user_create_id'] = $user->_id;
    $log['order_id'] = $order->_id;
    $log['product_id'] = $order->product_id;
    $log['source_id'] = $order->source_id;
    $log['status'] = 1;
    $this->activity->create($log);
  }

  public function newOrderShip($order, $request)
  {
    $data = array();
    $data['order_id'] = $order->_id;

    if (empty($order->load('ship')->ship) || $this->customer->isUserSale() || $this->customer->isCare()) {
      $data['user_create_id'] = $this->customer->info()->_id;
    }

    // Là sale
    // request gửi lên phải là success
    // chưa tồn tại ngày chốt đơn.\
    if (($this->customer->isSale() || $this->customer->isCare()) && $request['reason'] == "success" && empty($order->load('ship')->ship->date_success_order)) {
      $data['date_success_order'] = $this->timeNow;
    }

    $data['name'] = $request['ship-name'];
    $data['phone'] = $request['ship-phone'];
    $data['provin'] = $request['ship-provin'];
    $data['district'] = $request['ship-district'];
    $data['town'] = $request['ship-town'];
    $data['address'] = $request['ship-address'];
    $data['reason'] = $request['reason'];
    $products = array();
    if (isset($request['ship-product'])) {
      foreach ($request['ship-product'] as $key => $item) {
        if (empty($request['ship-price'][$key])) {
          $request['ship-price'][$key] = 0;
        }
        $product = array('product_id' => $item, 'amount' => str_replace(",", "", $request['ship-amount'][$key]), 'price' => str_replace(",", "", $request['ship-price'][$key]));
        array_push($products, $product);
      }
    }
    $data['product'] = $products;
    $data['note_ship'] = $request['ship-note_ship'];
    $data['note_delivery'] = $request['ship-note_delivery'];
    $data['discount'] = str_replace(",", "", $request['ship-discount']);
    $data['transport'] = str_replace(",", "", $request['ship-transport']);
    $data['charge'] = str_replace(",", "", $request['ship-charge']);
    $data['total'] = str_replace(",", "", $request['ship-total']);
    $this->ship->updateByOrderId($order->_id, $data);
  }

  public function getCustomerFromArrayId($array)
  {
    if (!empty($array)) {
      return $this->customer->get(['_id' => $array]);
    } else {
      return [];
    }
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

  public function filterByPermissionCustomer($request)
  {
    $customer = $this->customer->info();
    $company = $customer->load('company')->company;


    if ($company->divideOrder == 1) {
      $request['divideOrder'] = 1;
      $request = $this->filterByDivideOrder($customer, $request);
    } else {
      $request['divideOrder'] = 0;
      $request = $this->filterByAutoGetOrder($customer, $request);
    }

    return $request;
  }

  public function filterByDivideOrder($customer, $request)
  {
    if ($this->customer->isAdminMkt()) {
      $request['company_id'] = $customer->company_id;
    } elseif ($this->customer->isAdminSale() || $this->customer->isVandon()) {
      $connect = $customer->load('companySale')->companySale;
      $request['product_id'] = $connect->pluck('product_id')->toArray();
      $request['company_id'] = $connect->pluck('company_mkt_id')->toArray();
    } elseif ($this->customer->isMkt()) {
      $filter = $this->getMembersGroupByCustomer($customer);
      $request['user_create_id'] = $filter;
    } elseif ($this->customer->isSale()) {
      $filter = $this->getMembersGroupByCustomer($customer);
      $request['user_reciver_id'] = $filter;
    }

    if ($this->customer->isSale()) {
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

  public function filterByAutoGetOrder($customer, $request)
  {
    if ($this->customer->isAdminMkt()) {
      $request['company_id'] = $customer->company_id;
    } elseif ($this->customer->isAdminSale() || $this->customer->isVandon()) {
      if ($customer->company->company_type == "all") {
        $request['company_id'] = $customer->company->_id;
      }else{
        $connect = $customer->load('companySale')->companySale;
        $request['product_id'] = $connect->pluck('product_id')->toArray();
        $request['company_id'] = $connect->pluck('company_mkt_id')->toArray();
      }
    } elseif ($this->customer->isMkt()) {
      $filter = $this->getMembersGroupByCustomer($customer);
      $request['user_create_id'] = $filter;
    } elseif ($this->customer->isSale()) {
      if ($customer->company->company_type == "all") {
        $request['company_id'] = $customer->company->_id;
      }else{
        $connect = $customer->load('companySale')->companySale;
        $request['product_id'] = $connect->pluck('product_id')->toArray();
        $request['company_id'] = $connect->pluck('company_mkt_id')->toArray();
      }
      
      // $request['date_reciver'] = null;
      $request['user_reciver_id'] = array(null, $customer->_id);
    }


    if (!empty($request['user_id'])) {
      $request['user_create_id'] = $request['user_id'];
    }

    

    return $request;
  }

  public function searchPhone(SearchPhoneRequest $request)
  {
    $customer = $this->customer->info();
    $request = $this->acceptRequest($request);
    $phone = $request['phone'];
    $phoneRemoveFirstChracter = $phoneAddFirstChracter = $phone;
    if ($phone[0] == 0) {
      $phoneRemoveFirstChracter = substr($phone, 1, strlen($phone));
    } else {
      $phoneAddFirstChracter = 0 . $phone;
    }
    $data['phone'] = [$phone, $phoneRemoveFirstChracter, $phoneAddFirstChracter];
    $data['company_id'] = $customer->company_id;
    $res = $this->order->searchPhone($data)->load('customerCreateOrder')->load('product');
    if ($res) {
      $this->response['success'] = true;
      $this->response['msg'] = "Lấy data thành công!";
      $this->response['data'] = $res;
    }
    return response()->json($this->response, 200);
  }

  public function defaultRequest($request)
  {
    $request['limit'] = !empty($request['limit']) ? (int) $request['limit'] : 20;
    if ($this->customer->isVandon() && $this->customer->isUser()) {
      $request['reason'] = "success";
    }

    $request['filter_date_by'] = !empty($request['filter_date_by']) ? $request['filter_date_by'] : 'created_at';

    return $request;
  }

  private function hideNumberPhone($lists)
  {
    foreach ($lists as $item) {
      if (!empty($item->phone)) {
        $item->phone = str_repeat("*", strlen($item->phone) - (strlen($item->phone) - 3)) . substr($item->phone, 3, strlen($item->phone));
      }
    }
    return $lists;
  }

  public function acceptRequest($request)
  {
    if (!empty($request->note)) {
      $request['note'] = XSSCleaner::clean($request->note);
    }
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
      'paginate',
      ''
    );
  }
}
