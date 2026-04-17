<?php

namespace App\Http\Requests\Admin\Product;

use App\Models\Products;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $slug = $this->route('slug');
        $product = Products::where('slug', $slug)->first();
        $id = $product ? $product->id : null;

        return [
            'name'              => 'required|string|max:255',
            'category_id'       => 'required|integer|exists:categories,id',
            'brand'             => 'required|string|max:100',
            'model'             => 'nullable|string|max:100|unique:products,model,' . $id,
            'warranty_months'   => 'nullable|integer|min:0',
            'price'             => 'required|integer|min:0',
            'stock'             => 'required|integer|min:0',
            'description'       => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên sản phẩm không được lệnh trống.',
            'model.unique'  => 'Mã sản phẩm (Model) này đã tồn tại trong hệ thống.',
        ];
    }
}
