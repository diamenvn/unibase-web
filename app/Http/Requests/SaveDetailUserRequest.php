<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SaveDetailUserRequest extends FormRequest
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
            'name' => 'required|max:100|min:5',
            'type_account' => 'required|in:mkt,sale,hcns,bill',
            'permission' => 'required|in:admin,user',
            'active' => 'required|in:1,0'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Bạn vui lòng nhập tên',
            'name.max' => 'Tên không quá 255 kí tự',
            'name.min' => 'Tên không ngắn hơn 5 kí tự',
            'permission.in' => 'Permission không đúng định dạng',
            'type_account.in' => 'Loại tài khoản không đúng định dạng',
            'active.required' => 'Active không được bỏ trống'
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
