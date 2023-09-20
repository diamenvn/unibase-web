<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:100|min:2',
            'username' => 'required|max:100|min:5',
            'newpass' => 'required|max:100|min:6',
            'type_account' => 'required|in:mkt,sale,hcns,bill,care',
            'permission' => 'required|in:admin,user',
            'active' => 'required|in:1,0'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Bạn vui lòng nhập họ và tên',
            'name.max' => 'Tên không quá 255 kí tự',
            'name.min' => 'Tên không ngắn hơn 2 kí tự',
            'username.required' => 'Bạn vui lòng nhập tên tài khoản',
            'username.max' => 'Tên tài khoản không quá 255 kí tự',
            'username.min' => 'Tên tài khoản không ngắn hơn 5 kí tự',
            'newpass.required' => 'Bạn vui lòng nhập mật khẩu',
            'newpass.max' => 'Mật khẩu không quá 255 kí tự',
            'newpass.min' => 'Mật khẩu không ngắn hơn 6 kí tự',
            'permission.in' => 'Permission không đúng định dạng',
            'type_account.in' => 'Loại tài khoản không đúng định dạng',
            'active.required' => 'Active không được bỏ trống'
        ];
    }

    protected function failedValidation(Validator $validator) {
        $response = new JsonResponse(['success' => false, 'msg' => $validator->errors()->first(), 'data' => [], 'creation_time' => time()], 200);
        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
