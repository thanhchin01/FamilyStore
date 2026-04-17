<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;

class ProductController extends Controller
{
    // Danh sách sản phẩm
    // public function index(Request $request)
    // {
    //     $query = Products::query();

    //     // Lọc theo danh mục
    //     if ($request->filled('category')) {
    //         $query->where('category', $request->category);
    //     }

    //     // Lọc theo hãng
    //     if ($request->filled('brand')) {
    //         $query->where('brand', $request->brand);
    //     }

    //     // Tìm kiếm tên
    //     if ($request->filled('keyword')) {
    //         $query->where('name', 'like', '%' . $request->keyword . '%');
    //     }

    //     $products = $query
    //         ->orderByDesc('id')
    //         ->paginate(10);

    //     return view('admin.layouts.product.index', compact('products'));
    // }
    
    /**
     * Thêm sản phẩm
     */
    public function store(StoreProductRequest $request)
    {
        $product = Products::create([
            'name'                  => $request->name,
            'slug'                  => $this->generateUniqueSlug($request->name),
            'category_id'           => $request->category_id,
            'brand'                 => $request->brand,
            'model'                 => $request->model,
            'price'                 => $request->price,
            'warranty_months'       => $request->warranty_months ?? 0,
            'stock'                 => 0, // Khởi tạo bằng 0, inventory service sẽ cộng lên
            'description'           => $request->description,
            'is_active'             => true,
        ]);

        // Nếu có số lượng tồn đầu kỳ > 0, ghi nhận vào kho
        if ($request->stock > 0) {
            $inventoryService = app(\App\Services\InventoryService::class);
            $inventoryService->importProduct($product->id, (int) $request->stock, 'Khởi tạo tồn kho khi thêm sản phẩm');
        }

        return redirect()
            ->back()
            ->with('success', 'Thêm sản phẩm thành công');
    }

    // Cập nhật sản phẩm
    public function update(UpdateProductRequest $request, $slug)
    {
        $product = Products::where('slug', $slug)->firstOrFail();

        $product->update([
            'name'            => $request->name,
            'slug'            => $this->generateUniqueSlug($request->name, $product->id),
            'category_id'     => $request->category_id,
            'brand'           => $request->brand,
            'model'           => $request->model,
            'warranty_months' => $request->warranty_months ?? 0,
            'price'           => $request->price,
            'stock'           => $request->stock,
            'description'     => $request->description,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Cập nhật sản phẩm thành công');
    }

    /**
     * Ngừng bán (soft)
     */
    public function disable($slug)
    {
        $product = Products::findOrFail($slug);
        // Sửa lỗi: findOrFail tìm theo ID, cần tìm theo slug
        // $product = Products::where('slug', $slug)->firstOrFail();
        $product->update([
            'is_active' => false
        ]);

        return redirect()
            ->back()
            ->with('success', 'Sản phẩm đã được ngừng bán');
    }

    /**
     * Xoá sản phẩm (nếu muốn xoá cứng)
     */
    public function destroy($slug)
    {
        $product = Products::findOrFail($slug);
        // Sửa lỗi: findOrFail tìm theo ID, cần tìm theo slug
        // $product = Products::where('slug', $slug)->firstOrFail();
        $product->delete();

        return redirect()
            ->back()
            ->with('success', 'Đã xoá sản phẩm');
    }

    private function generateUniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;

        $query = Products::where('slug', 'LIKE', $slug . '%');

        // Nếu update thì bỏ qua chính nó
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $count = $query->count();

        if ($count > 0) {
            $slug = $originalSlug . '-' . ($count + 1);
        }

        return $slug;
    }
}
