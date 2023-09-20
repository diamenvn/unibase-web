<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class SaveOrderRequest extends FormRequest
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
            'name' => 'required',
            'phone' => 'required',
            
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'Bạn vui lòng nhập tên khách hàng',
            'phone.required' => 'Bạn vui lòng nhập số điện thoại khách hàng',
            'product_id.required' => 'Bạn vui lòng nhập sản phẩm',
            'user_create_id.required' => 'Thiếu thông tin người tạo',
            'source_id.required' => 'Thiếu nguồn sản phẩm',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
