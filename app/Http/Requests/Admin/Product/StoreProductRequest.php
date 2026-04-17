<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'             => 'required|string|max:255',
            'category_id'      => 'required|integer|exists:categories,id',
            'brand'            => 'required|string|max:100',
            'model'            => 'nullable|string|max:100|unique:products,model',
            'warranty_months'  => 'nullable|integer|min:0',
            'price'            => 'required|integer|min:0',
            'stock'            => 'required|integer|min:0',
            'description'      => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'Tên sản phẩm không được để trống.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists'   => 'Danh mục không hợp lệ.',
            'brand.required'       => 'Vui lòng nhập thương hiệu.',
            'model.unique'         => 'Mã sản phẩm (Model) này đã tồn tại trong hệ thống.',
            'price.required'       => 'Giá sản phẩm không được để trống.',
            'price.integer'        => 'Giá phải là số nguyên.',
            'stock.required'       => 'Số lượng tồn kho không được để trống.',
        ];
    }
}
