<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Services\Admin\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Danh sách tồn kho để kiểm tra nhanh
     */
    public function index(Request $request)
    {
        $products = Products::select('id', 'name', 'stock', 'price', 'brand', 'model', 'image')
            ->when($request->search, function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('model', 'LIKE', '%' . $request->search . '%');
            })
            ->when($request->low_stock, function($q) {
                $q->where('stock', '<=', 5);
            })
            ->orderBy('stock', 'asc')
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'data'    => $products
        ]);
    }

    /**
     * Nhập hàng nhanh qua App
     */
    public function quickImport(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'note'       => 'nullable|string'
        ]);

        try {
            $product = $this->inventoryService->importProduct(
                $request->product_id, 
                $request->quantity, 
                0,
                $request->note ?? 'Nhập hàng nhanh từ Mobile App'
            );


            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật tồn kho mới',
                'new_stock' => $product->stock
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}
