<?php

namespace App\Http\Requests\Client\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required_without:phone', 'nullable', 'string', 'email', 'max:255', 'unique:users'],
            'phone'    => ['required_without:email', 'nullable', 'string', 'max:20', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'otp'      => ['required', 'string', 'size:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Vui lòng nhập họ và tên.',
            'email.required'    => 'Vui lòng nhập địa chỉ email.',
            'email.email'       => 'Địa chỉ email không hợp lệ.',
            'email.unique'      => 'Địa chỉ email này đã được sử dụng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min'      => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ];
    }
}
