<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\CreateCustomerRequest;
use App\Http\Requests\SaveDetailUserRequest;
use App\Http\Requests\SearchPhoneRequest;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\SettingService;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Hash;

class ApiCustomerController extends Controller
{
    public function __construct(UserService $user, SettingService $setting, CustomerService $customerService)
    {
        $this->user = $user;
        $this->setting = $setting;
        $this->customerService = $customerService;
        $this->response['msg'] = "Error!";
        $this->response['success'] = false;
        $this->response['data'] = [];
    }

    public function lists(Request $request)
    {
        $user = $this->user->info();
        $customers = $this->customerService->queryAllByCompanyId($user->company_id, $request);
        if ($customers) {
            $customers = $customers->paginate($request['limit'])->setPath('');
        }
        if ($customers) {
            $this->response['success'] = true;
            $this->response['msg'] = 'Lấy thông tin thành công!';
            $this->response['data'] = $customers;
        }
        return response()->json($this->response, 200); 
    }

    public function store(CreateCustomerRequest $request)
    {
        $request = $this->acceptRequest($request);
        $user = $this->user->info();

        $request['status'] = 1;
        $request['created_by'] = $user->_id;
        $request['company_id'] = $user->company_id;
        $request['customer_id'] = date("mdHms");

        $res = $this->customerService->create($request);
        if (!empty($res)) {
        $this->response['success'] = true;
        $this->response['msg'] = "Đã tạo khách hàng thành công";
        } else {
        $this->response['msg'] = "Tạo khách hàng thất bại";
        }
        return response()->json($this->response, 200);
    }

    public function update($customerId, CreateCustomerRequest $request)
    {
        $request = $this->acceptRequest($request);
        $user = $this->user->info();
        $findCustomer = $this->customerService->first($customerId);
        if (empty($findCustomer)) {
            $this->response['msg'] = 'Không tìm khách hàng này!';
            return response()->json($this->response, 404);
        }

        $update = $this->customerService->update($findCustomer, $request);
        if (!empty($update)) {
            $this->response['success'] = true;
            $this->response['msg'] = 'Cập nhật khách hàng thành công';
        }

        return response()->json($this->response, 200);
    }

    public function getDetail($id)
    {
        $user = $this->user->info();
        $allowResponse = [
            '_id',
            'company_id',
            'name',
            'username',
            'email',
            'phone',
            'permission',
            'type_account',
            'active',
            'created_at'
        ];
        $user = $this->user->first($id, $allowResponse);
        if (empty($user)) {
            $this->response['msg'] = 'Không tìm thấy tài khoản này!';
            return response()->json($this->response, 404);
        }

        if ($this->checkPermission($user, $user)) {
            $this->response['success'] = true;
            $this->response['msg'] = 'Lấy thông tin thành công!';
            $this->response['data'] = $user;
        } else {
            $this->response['msg'] = 'Bạn không đủ quyền';
        }

        return response()->json($this->response, 200);
    }

    public function saveDetail($id, SaveDetailUserRequest $request)
    {
        $user = $this->user->info();
        $request = $this->acceptRequest($request);
        $user = $this->user->first($id);

        if (empty($user)) {
            $this->response['msg'] = 'Không tìm thấy tài khoản này!';
            return response()->json($this->response, 404);
        }
        if ($this->checkPermission($user, $user)) {

            if (!empty($request['newpass'])){
                $data['password'] = Hash::make($request['newpass']);
            }
            $data['name'] = $request['name'];
            $data['phone'] = $request['phone'];
            $data['email'] = $request['email'];
            $data['active'] = (int) $request['active'];
            if ($this->user->isAdmin()) { // Only admin
                $data['type_account'] = $request['type_account'];
                $data['permission'] = $request['permission'];
            }

            $update = $this->user->update($user, $data);
            if (!empty($update)) {
                $this->response['success'] = true;
                $this->response['msg'] = 'Cập nhật tài khoản thành công';
            }
        } else {
            $this->response['msg'] = 'Bạn không đủ quyền';
        }

        return response()->json($this->response, 200);
    }

    public function saveCreate(CreateUserRequest $request)
    {
        $user = $this->user->info();
        $request = $this->acceptRequest($request);
        
        $group = $this->setting->findGroupByCustomerId($user->_id);

        if ($this->user->isAdmin() || !empty($group)) {
            $user = $this->user->firstByUsername($request['username']);
            if (!empty($user)) {
                $this->response['msg'] = 'Tài khoản này đã tồn tại!';
                return response()->json($this->response, 200);
            }

            $data['password'] = Hash::make($request['newpass']);
            $data['username'] = $request['username'];
            $data['name'] = $request['name'];
            $data['phone'] = $request['phone'];
            $data['email'] = $request['email'];
            $data['company_id'] = $user->company_id;

            if ($this->user->isAdmin()) { // Only admin
                $data['type_account'] = $request['type_account'];
                $data['permission'] = $request['permission'];
            }else{
                $data['type_account'] = $user->type_account;
                $data['permission'] = 'user';
            }

            $data['active'] = 1;
            $data['status'] = 1;
            $data['avatar'] = 'https://banhang.myteam.com.vn/assets/site/theme/images/logo.png';
            $res = $this->user->create($data);
            if (!empty($res)) {
                $this->response['success'] = true;
                $this->response['msg'] = 'Tạo mới tài khoản thành công';
                if (!empty($group)){
                    $this->addMemberToGroup($group, $res);
                }
            }
        } else {
            $this->response['msg'] = 'Bạn không đủ quyền';
        }

        return response()->json($this->response, 200);
    }

    public function addMemberToGroup($group, $user)
    {
        if (!empty($group)) {
            foreach ($group as $item) {
                $members = [];
                if (!empty($item->members)) {
                    foreach ($item->members as $value) {
                        array_push($members, $value);
                    }
                }
                array_push($members, $user->_id);
                $data['members'] = $members;
                $this->setting->update($item, $data);
            }
        }
    }

    public function updateActivity($customerId, Request $request)
    {
        $user = $this->user->info();
        $request = $this->acceptRequest($request);

        $request['user_create_id'] = $user->_id;
        $request['reason'] = "wait";
        $request['status'] = 1;
        $request['customer_id'] = $customerId;

        $update = $this->customerService->createActivity($request);
        if ($update) {
            $this->response['success'] = true;
            $this->response['msg'] = "Cập nhật thành công!";
            $this->response['data'] = $update;
        }
        return response()->json($this->response, 200);

    }

    public function searchPhone(SearchPhoneRequest $request)
    {
        $user = $this->user->info();
        $request = $this->acceptRequest($request);
        $phone = $request['phone'];
        $phoneRemoveFirstChracter = $phoneAddFirstChracter = $phone;
        if ($phone[0] == 0) {
        $phoneRemoveFirstChracter = substr($phone, 1, strlen($phone));
        } else {
        $phoneAddFirstChracter = 0 . $phone;
        }
        $data['phone'] = [$phone, $phoneRemoveFirstChracter, $phoneAddFirstChracter];
        $data['company_id'] = $user->company_id;
        $res = $this->customerService->searchPhone($data);
        if ($res) {
        $this->response['success'] = true;
        $this->response['msg'] = "Lấy data thành công!";
        $this->response['data'] = $res;
        }
        return response()->json($this->response, 200);
    }

    public function remove(Request $request)
    {
        $request = $request->only('_id');
    
        $customers = $this->customerService->firstById($request['_id']);

        if ($customers) {
            $res = $this->customerService->updateById($request['_id'], ['status' => 0]);
            if ($res) {
                $this->response['success'] = true;
                $this->response['msg'] = "Đã xoá thành công!";
            }
        }else{
        $this->response['msg'] = "Không thể xóa";
        }
        
        return response()->json($this->response, 200);
    }

    public function checkPermission($targetUser, $user)
    {
        $allow = false;
        $group = $this->setting->findGroupByCustomerId($targetUser->_id);
        if (!empty($group)) {
            $members = $group->pluck('members')->toArray();
            if (in_array($targetUser->_id, $this->arrayMerge($members))) {
                $allow = true;
            }
        }

        if ($user->company_id !== $user->company_id) {
            $allow = false;
        }
        return $this->user->isAdmin() || $user->_id == $user->_id || $allow;
    }

    public function defaultRequest($request)
    {
        $request['limit'] = isset($request['limit']) ? (int) $request['limit'] : 10;
        $request['reason'] = 'success'; // Chỉ lấy đơn đã chốt
        return $request;
    }

    public function arrayMerge($array)
    {
        if (!empty($array)) {
            return array_unique(array_merge(...$array));
        } else {
            return [];
        }
    }

    public function acceptRequest($request)
    {
        return $request->only(
            'username',
            'company_id',
            'newpass',
            'name',
            'email',
            'phone',
            'type_account',
            'permission',
            'active',
            'source_id',
            'note',
            'address',
            'msdn_value',
            'mst_value',
            'company_name',
            'rank',
            'activity_value'
        );
    }
}
