<?php

namespace App\Http\Requests\Admin\Sale;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'customer_type' => 'required|in:guest,customer',
            'paid_amount'   => 'nullable|integer|min:0',
        ];

        if ($this->has('items') && is_array($this->items)) {
            $rules['items']              = 'required|array|min:1';
            $rules['items.*.product_id'] = 'required|exists:products,id';
            $rules['items.*.quantity']   = 'required|integer|min:1';
            $rules['items.*.price']      = 'required|integer|min:0';
        } else {
            $rules['product_id'] = 'required|exists:products,id';
            $rules['quantity']   = 'required|integer|min:1';
            $rules['price']      = 'required|integer|min:0';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'customer_type.required' => 'Vui lòng chọn loại khách hàng.',
            'items.required'         => 'Danh sách sản phẩm không được trống.',
            'product_id.required'    => 'Vui lòng chọn sản phẩm.',
            'quantity.min'           => 'Số lượng phải lớn hơn 0.',
        ];
    }
}
