<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

class LoginRequest extends FormRequest
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
            'username' => 'required|max:255|min:5',
            'password' => 'required|max:255|min:6'
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'Bạn vui lòng nhập tên tài khoản',
            'username.max' => 'Tên tài khoản không quá 255 kí tự',
            'username.min' => 'Tên tài khoản không ngắn hơn 5 kí tự',
            'password.required' => 'Bạn vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu quá ngắn, không đúng định dạng'
        ];
    }

    protected function failedValidation(Validator $validator) {
        return view('site.auth.login')->with('error', $validator->errors());
    }
}
