<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

class SaveApiImportRequest extends FormRequest
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
            'product_id' => 'required',
            'status' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên API không được để trống',
            'product_id.required' => 'Tên sản phẩm không được để trống',
            'status.required' => 'Trạng thái không được để trống'
        ];
    }

    protected function failedValidation(Validator $validator) {
        $response = new JsonResponse(['success' => false, 'msg' => $validator->errors()->first(), 'data' => [], 'creation_time' => time()], 200);
        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
