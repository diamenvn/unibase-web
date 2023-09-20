<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\SaveDetailUserRequest;
use App\Services\CustomerService;
use App\Services\SettingService;
use Illuminate\Support\Facades\Hash;

class ApiUserController extends Controller
{
    public function __construct(CustomerService $customer, SettingService $setting)
    {
        $this->customer = $customer;
        $this->setting = $setting;
        $this->response['msg'] = "Error!";
        $this->response['success'] = false;
        $this->response['data'] = [];
    }

    public function getDetail($id)
    {
        $customer = $this->customer->info();
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
        $user = $this->customer->first($id, $allowResponse);
        if (empty($user)) {
            $this->response['msg'] = 'Không tìm thấy tài khoản này!';
            return response()->json($this->response, 404);
        }

        if ($this->checkPermission($customer, $user)) {
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
        $customer = $this->customer->info();
        $request = $this->acceptRequest($request);
        $user = $this->customer->first($id);

        if (empty($user)) {
            $this->response['msg'] = 'Không tìm thấy tài khoản này!';
            return response()->json($this->response, 404);
        }
        if ($this->checkPermission($customer, $user)) {

            if (!empty($request['newpass'])){
                $data['password'] = Hash::make($request['newpass']);
            }
            $data['name'] = $request['name'];
            $data['phone'] = $request['phone'];
            $data['email'] = $request['email'];
            $data['active'] = (int) $request['active'];
            if ($this->customer->isAdmin()) { // Only admin
                $data['type_account'] = $request['type_account'];
                $data['permission'] = $request['permission'];
            }

            $update = $this->customer->update($user, $data);
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
        $customer = $this->customer->info();
        $request = $this->acceptRequest($request);
        
        $group = $this->setting->findGroupByCustomerId($customer->_id);

        if ($this->customer->isAdmin() || !empty($group)) {
            $user = $this->customer->firstByUsername($request['username']);
            if (!empty($user)) {
                $this->response['msg'] = 'Tài khoản này đã tồn tại!';
                return response()->json($this->response, 200);
            }

            $data['password'] = Hash::make($request['newpass']);
            $data['username'] = $request['username'];
            $data['name'] = $request['name'];
            $data['phone'] = $request['phone'];
            $data['email'] = $request['email'];
            $data['company_id'] = $customer->company_id;

            if ($this->customer->isAdmin()) { // Only admin
                $data['type_account'] = $request['type_account'];
                $data['permission'] = $request['permission'];
            }else{
                $data['type_account'] = $customer->type_account;
                $data['permission'] = 'user';
            }

            $data['active'] = 1;
            $data['status'] = 1;
            $data['avatar'] = 'https://banhang.myteam.com.vn/assets/site/theme/images/logo.png';
            $res = $this->customer->create($data);
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

    public function removeUser($id)
    {
        if ($this->customer->isAdmin()) {
            $user = $this->customer->first($id);
            if (isset($user) && $this->customer->info()->company_id != $user->company_id) {
                $this->response['msg'] = 'Không tồn tại tài khoản này';
            }
            $remove = $this->customer->remove($id);
            if ($remove) {
                $this->response['success'] = true;
                $this->response['msg'] = 'Xoá tài khoản thành công';
            }
        }else{
            $this->response['msg'] = 'Bạn không đủ quyền thực hiện';
        }
        return response()->json($this->response, 200);
    }

    public function checkPermission($customer, $user)
    {
        $allow = false;
        $group = $this->setting->findGroupByCustomerId($customer->_id);
        if (!empty($group)) {
            $members = $group->pluck('members')->toArray();
            if (in_array($user->_id, $this->arrayMerge($members))) {
                $allow = true;
            }
        }

        if ($user->company_id !== $customer->company_id) {
            $allow = false;
        }
        return $this->customer->isAdmin() || $user->_id == $customer->_id || $allow;
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
            'active'
        );
    }
}
