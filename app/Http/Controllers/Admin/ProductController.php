<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'category_id'      => 'required|string|max:100',
            'brand'            => 'required|string|max:100',
            'model'            => 'nullable|string|max:100',
            'warranty_months'  => 'nullable|integer|min:0',
            'price'            => 'required|integer|min:0',
            'stock'            => 'required|integer|min:0',
            'description'      => 'nullable|string',
        ]);

        Products::create([
            'name'                  => $request->name,
            // 'slug' => Str::slug($request->name),
            'slug'                  => $this->generateUniqueSlug($request->name),
            'category_id'           => $request->category_id,
            'brand'                 => $request->brand,
            'model'                 => $request->model,
            'price'                 => $request->price,
            'warranty_months'       => $request->warranty_months ?? 0,
            'stock'                 => $request->stock,
            'description'           => $request->description,
            'is_active'             => true,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Thêm sản phẩm thành công');
    }

    // Cập nhật sản phẩm
    public function update(Request $request, $slug)
    {
        $request->validate([
            // 'id' => 'required|exists:products,id',
            'name'              => 'required|string|max:255',
            // 'category_id'       => 'required|string|max:100',
            'category_id'     => 'required|integer|exists:categories,id',
            'brand'             => 'required|string|max:100',
            'model'             => 'nullable|string|max:100',
            'warranty_months'   => 'nullable|integer|min:0',
            'price'             => 'required|integer|min:0',
            'stock'             => 'required|integer|min:0',
            'description'       => 'nullable|string',
        ]);

        // $product = Products::findOrFail($request->id);
        $product = Products::where('slug', $slug)->firstOrFail();
        $product->update([
            'name'            => $request->name,
            // 'slug' => Str::slug($request->name),
            'slug'            => $this->generateUniqueSlug($request->name, $product->id),
            'category_id'     => $request->category_id,
            'brand'           => $request->brand,
            'model'           => $request->model,
            'warranty_months' => $request->warranty_months ?? 0,
            'price'           => $request->price,
            'stock'           => $request->stock,
            'description'     => $request->description,
            // 'is_active'        => true,

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
