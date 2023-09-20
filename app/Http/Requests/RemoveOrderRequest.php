<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

class RemoveOrderRequest extends FormRequest
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
            '_id' => 'required'
            
        ];
    }

    public function messages()
    {
        return [
            '_id.required' => 'Bạn chưa nhập id đơn hàng cần xoá'
        ];
    }

    protected function failedValidation(Validator $validator) {
        $response = new JsonResponse(['success' => false, 'msg' => $validator->errors()->first(), 'data' => [], 'creation_time' => time()], 200);
        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
