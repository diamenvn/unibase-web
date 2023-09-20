<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RemoveProductRequest;
use App\Http\Requests\SaveAddProductRequest;
use App\Services\CustomerService;
use App\Services\SettingService;
use App\Http\Requests\SaveApiImportRequest;
use App\Services\OrderActivityService;
use App\Services\OrderListService;
use Illuminate\Http\Request;

class ApiSettingController extends Controller
{
    public function __construct(CustomerService $customer, SettingService $setting, OrderListService $order, OrderActivityService $activity)
    {
        $this->customer = $customer;
        $this->setting = $setting;
        $this->order = $order;
        $this->activity = $activity;
        $this->response['msg'] = "Error!";
        $this->response['success'] = false;
        $this->response['data'] = [];
    }

    public function createGroup(Request $request)
    {
        $customer = $this->customer->info();
        $request = $request->only('title');
        if (empty($request['title'])) {
            $this->response['msg'] = "Vui lòng nhập tên nhóm";
            return response()->json($this->response);
        }

        if ($this->customer->isAdmin()) {
            $data['user_create_id'] = $customer->_id;
            $data['company_id'] = $customer->company_id;
            $data['title'] = $request['title'];
            $data['status'] = 1;
            $res = $this->setting->createGroup($data);
            if (!empty($res)) {
                $continue = route('site.setting.group', ['group_id' => $res->_id]);
                $dataResponse['url'] =  $continue;
                $this->response['success'] = true;
                $this->response['msg'] = "Tạo nhóm thành công!";
                $this->response['data'] = $dataResponse;
            }
        } else {
            $this->response['msg'] = "Bạn không đủ quyền để tạo";
        }
        return response()->json($this->response, 200);
    }

    public function updateGroup($id, Request $request)
    {
        $customer = $this->customer->info();
        $request = $request->only('leader', 'user_id');
        $group = $this->setting->firstGroup($id);
        if (empty($group)) {
            $this->response['msg'] = "Không tìm thấy nhóm này";
            return response()->json($this->response, 404);
        }

        if ($this->customer->isAdmin() && $customer->company_id == $group->company_id) {
            $customerInCompany = $this->customer->getByCompanyId($customer->company_id)->pluck('_id')->toArray();
            $listMembers = $listLeaders = array();

            foreach ($request['user_id'] as $key => $value) {
                //Valid customerId send from api
                if (!in_array($value, $customerInCompany)) continue;
                if (!empty($request['leader'][$key])) {
                    array_push($listLeaders, $request['leader'][$key]);
                }
                array_push($listMembers, $value);
            }

            $data['members'] = $listMembers;
            $data['leaders'] = $listLeaders;
            $res = $this->setting->update($group, $data);
            if ($res) {
                $this->response['success'] = true;
                $this->response['msg'] = "Cập nhật thành công";
            } else {
                $this->response['msg'] = "Cập nhật thất bại";
            }
        } else {
            $this->response['msg'] = "Bạn không đủ quyền để thêm!";
        }
        return response()->json($this->response, 200);
    }

    public function getListAPIImport()
    {
        $customer = $this->customer->info();
        $lists = $this->setting->getByCustomerId($customer->_id);
        $lists = array(
            'result'       => $lists,
            'pagination' => (string) $lists->links()
        );
        if ($lists) {
            $this->response['msg'] = "Lấy data thành công!";
            $this->response['success'] = true;
            $this->response['data'] = $lists;
        }

        return response()->json($this->response, 200);
    }

    public function getDetailtAPIImport($id)
    {
        if (empty($id)){
            $this->response['msg'] = "Không tìm thấy dữ liệu";
            return response()->json($this->response, 404);
        }
        $customer = $this->customer->info();
        $res = $this->setting->firstImportByCustomerId($id, $customer->_id);
        if ($res) {
            $this->response['success'] = true;
            $this->response['msg'] = 'Lấy dữ liệu thành công';
            $this->response['data'] = $res;
        }

        return response()->json($this->response, 200);
    }

    public function pushDataImportFromAPI($id, Request $request)
    {
        $request = $request->only('name', 'message', 'address', 'phone', 'email', 'address');
        $import = $this->setting->firstImport($id);

        if (empty($import)) {
            $this->response['msg'] = "Không tìm thấy dữ liệu";
            return response()->json($this->response, 404);
        }
        if ($import->status == 0) {
            $this->response['msg'] = "API này đã dừng hoạt động";
            return response()->json($this->response, 404);
        }
        $customer = $this->customer->first($import->user_create_id);
        if (empty($customer)) {
            $this->response['msg'] = "Không tìm thấy tài khoản này";
            return response()->json($this->response, 404);
        }
        $address = $link = '';
        if (!empty($request['address'])) {
            $address = $request['address'];
        }
        if (!empty($request['message'])) {
            $address = $request['message'];
            $request['message'] = null;
        }
        if (!empty($request['link'])) {
            $link = $request['link'];
        }

        if (empty($request['name'])) {
            $this->response['msg'] = "Không được để trống trường tên";
            return response()->json($this->response, 500);
        }
        if (empty($request['phone'])) {
            $this->response['msg'] = "Không được để trống trường số điện thoại";
            return response()->json($this->response, 500);
        }
        $request['name'] = $request['name'];
        $request['phone'] = $request['phone'];
        $request['address'] = $address;
        $request['link'] = $link;
        $request['user_create_id'] = $customer->_id;
        $request['company_id'] = $customer->company_id;
        $request['product_id'] = $import->product_id;
        $request['status'] = 1;
        $res = $this->order->create($request);

        if ($res) {
            $this->createActivityLog($res);
            $this->response['success'] = true;
            $this->response['msg'] = 'Tạo thành công';
        }

        return response()->json($this->response, 200);
    }

    public function saveAddProduct(SaveAddProductRequest $request)
    {
        $customer = $this->customer->info();
        $request = $request->only('product_name', 'price', 'company_sale_id');


        if (isset($request['company_sale_id'])) {
            $connect = $this->setting->getCompanyConnectFromMKT($customer->company_id)->load('companySale')->pluck('company_sale_id')->toArray();
            if (!in_array($request['company_sale_id'], $connect)) {
                $this->response['msg'] = "Công ty này không thể nhận số của bạn";
                return response()->json($this->response, 401);
            }
        }else{
            $request['company_sale_id'] = $customer->company_id;
        }
        
        $request['company_mkt_id'] = $customer->company_id;
        $request['price'] = str_replace(",", "", $request['price']);
        $res = $this->setting->addProduct($request);
        if ($res) {
            $data['company_mkt_id'] = $customer->company_id;
            $data['company_sale_id'] = $request['company_sale_id'];
            $data['product_id'] = $res->_id;
            $addConnect = $this->setting->addConnectCompany($data);
            if ($addConnect) {
                $this->response['msg'] = "Tạo sản phẩm thành công!";
                $this->response['success'] = true;
            }
        }
        return response()->json($this->response, 200);
    }

    public function hiddenProduct(RemoveProductRequest $request)
    {
        if (!$this->customer->isAdmin()) {
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

    public function saveImportFromAPI(SaveApiImportRequest $request)
    {
        $customer = $this->customer->info();
        $request = $request->only('name', 'product_id', 'status');
        $request['user_create_id'] = $customer->_id;
        $request['status'] = (int) $request['status'];
        $res = $this->setting->createImportAPI($request);
        if ($res) {
            $this->response['msg'] = "Đã tạo Api token thành công!";
            $this->response['success'] = true;
            $this->response['data'] = $res;
        }

        return response()->json($this->response, 200);
    }

    public function editImportFromAPI($id, SaveApiImportRequest $request)
    {
        $request = $request->only('name', 'product_id', 'status');
        $request['status'] = (int) $request['status'];
        if (empty($id)){
            $this->response['msg'] = "Không tìm thấy dữ liệu";
            return response()->json($this->response, 404);
        }
        
        $customer = $this->customer->info();
        $import = $this->setting->firstImportByCustomerId($id, $customer->_id);
        if (empty($import)){
            $this->response['msg'] = "Bạn không thể chỉnh sửa Api này";
            return response()->json($this->response, 401);
        }
    
        $res = $this->setting->updateImportAPI($import, $request);
        if ($res) {
            $this->response['msg'] = "Chỉnh sửa Api token thành công!";
            $this->response['success'] = true;
            $this->response['data'] = $res;
        }

        return response()->json($this->response, 200);
    }

    public function removeImportFromAPI($id)
    {
        if (empty($id)){
            $this->response['msg'] = "Không tìm thấy dữ liệu";
            return response()->json($this->response, 404);
        }
        
        $customer = $this->customer->info();
        $import = $this->setting->firstImportByCustomerId($id, $customer->_id);
        if (empty($import)){
            $this->response['msg'] = "Bạn không thể chỉnh sửa Api này";
            return response()->json($this->response, 401);
        }
    
        $res = $this->setting->removeImportAPI($import->_id);
        if ($res) {
            $this->response['msg'] = "Xoá Api token thành công!";
            $this->response['success'] = true;
            $this->response['data'] = $res;
        }

        return response()->json($this->response, 200);
    }

    public function createActivityLog($order)
    {
        $user = $this->customer->info();
        $log['note'] = '<span class="co-purple bold">Note: Đơn hàng được tạo tự động bởi API Ladipage</span>';
        $log['user_create_id'] = $order->user_create_id;
        $log['order_id'] = $order->_id;
        $log['reason'] = 'wait';
        $log['status'] = 1;
        $log['group'] = 2;
        $this->activity->create($log);
    }
}
