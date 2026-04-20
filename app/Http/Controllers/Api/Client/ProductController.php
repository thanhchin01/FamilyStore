<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\Categories;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Danh sách sản phẩm kèm bộ lọc
     */
    public function index(Request $request)
    {
        $query = Products::where('is_active', true);

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo khoảng giá
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sắp xếp
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_asc': $query->orderBy('price', 'asc'); break;
            case 'price_desc': $query->orderBy('price', 'desc'); break;
            default: $query->orderBy('created_at', 'desc'); break;
        }

        $products = $query->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data'    => $products
        ]);
    }

    /**
     * Chi tiết sản phẩm
     */
    public function show($id)
    {
        $product = Products::with(['category', 'productImages'])
            ->where('is_active', true)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $product
        ]);
    }

    /**
     * Danh sách danh mục
     */
    public function categories()
    {
        $categories = Categories::where('is_active', true)->get();
        return response()->json([
            'success' => true,
            'data'    => $categories
        ]);
    }
}
