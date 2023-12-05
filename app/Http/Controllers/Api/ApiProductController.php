<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RemoveProductRequest;
use App\Http\Requests\SaveAddProductRequest;
use App\Services\UserService;
use App\Services\CatalogService;
use App\Services\OrderActivityService;
use App\Services\ApiService;
use Illuminate\Http\Request;

class ApiProductController extends Controller
{
    public function __construct(UserService $user, CatalogService $catalogService, OrderActivityService $activity, ApiService $apiService)
    {
        $this->user = $user;
        $this->catalogService = $catalogService;
        $this->activity = $activity;
        $this->apiService = $apiService;
        $this->response['msg'] = "Error!";
        $this->response['success'] = false;
        $this->response['data'] = [];
    }

    public function getListProduct(Request $request)
    {
        $fetch = $this->apiService->get(config('api.product.list'));
        $parse = $this->apiService->parse($fetch);
        
        if ($parse['success']) {
            $this->response['success'] = true;
            $this->response['msg'] = 'Lấy dữ liệu thành công';
            $this->response['data'] = $parse['response']->result;
          }
      
          return response()->json($this->response, 200);
    }

    public function getListProductOld(Request $request)
    {
        $user = $this->user->info();
        $res = $this->catalogService->paginateListProductByUserID($user->_id);
        if ($res) {
            $this->response['msg'] = "Lấy sản phẩm thành công!";
            $this->response['success'] = true;
            $this->response['data'] = $res;
        }
        return response()->json($this->response, 200);
    }

    public function store(SaveAddProductRequest $request)
    {
        $user = $this->user->info();
        $request = $request->only($this->exceptedQuery());
        $request['created_by'] = $user->_id;
        $res = $this->catalogService->createProduct($request);

        if ($res) {
            $this->response['msg'] = "Tạo sản phẩm thành công!";
            $this->response['success'] = true;
            $this->response['data'] = [
                "redirect_url" => route('site.product.detail', $res->_id)
            ];
        }
        return response()->json($this->response, 200);
    }

    public function hiddenProduct(RemoveProductRequest $request)
    {
        if (!$this->user->isAdmin()) {
            $this->response['msg'] = "Bạn không đủ quyền thực hiện";
            return response()->json($this->response, 200);
        }

        $res = $this->setting->removeProductById($request['id']);
        if ($res) {
            $this->response['msg'] = "Xoá sản phẩm thành công!";
            $this->response['success'] = true;
        }
        return response()->json($this->response, 200);
    }

    public function createActivityLog($order)
    {
        $user = $this->user->info();
        $log['note'] = '<span class="co-purple bold">Note: Đơn hàng được tạo tự động bởi API Ladipage</span>';
        $log['user_create_id'] = $order->user_create_id;
        $log['order_id'] = $order->_id;
        $log['reason'] = 'wait';
        $log['status'] = 1;
        $log['group'] = 2;
        $this->activity->create($log);
    }

    public function exceptedQuery()
    {
        return Array(
            'product_name', 'product_images' ,'product_material', 'product_color', 'product_size', 'product_weight', 'product_cost', 'product_ship_price', 'product_process_price', 'product_sku_name', 'total_all_order', 'total_order_proccessed', 'total_order_wait_process', 'total_order_proccessing'
        );
    }
}
