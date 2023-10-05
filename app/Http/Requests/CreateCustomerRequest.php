<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

class CreateCustomerRequest extends FormRequest
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
            'email' => 'required',
            'source_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Bạn vui lòng nhập tên khách hàng',
            'phone.required' => 'Bạn vui lòng nhập số điện thoại khách hàng',
            'email.required' => 'Bạn vui lòng nhập email khách hàng',
            'source_id.required' => 'Thiếu nguồn khách hàng',
        ];
    }

    protected function failedValidation(Validator $validator) {
        $response = new JsonResponse(['success' => false, 'msg' => $validator->errors()->first(), 'data' => [], 'creation_time' => time()], 200);
        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
