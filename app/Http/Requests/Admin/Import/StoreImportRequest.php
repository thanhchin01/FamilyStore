<?php

namespace App\Http\Requests\Admin\Import;

use Illuminate\Foundation\Http\FormRequest;

class StoreImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'note' => 'nullable|string|max:1000',
        ];

        if ($this->has('items') && is_array($this->items)) {
            $rules['items']              = 'required|array|min:1';
            $rules['items.*.product_id'] = 'required|exists:products,id';
            $rules['items.*.quantity']   = 'required|integer|min:1';
        } else {
            $rules['product_id'] = 'required|exists:products,id';
            $rules['quantity']   = 'required|integer|min:1';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Vui lòng chọn sản phẩm cần nhập.',
            'quantity.min'        => 'Số lượng nhập phải lớn hơn 0.',
        ];
    }
}
