<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function store(Request $request, InventoryService $inventoryService)
    {
        // ✅ Cho phép: nếu không có items[] thì dùng 1 dòng product_id + quantity
        $hasItemsArray = $request->has('items') && is_array($request->items);

        if ($hasItemsArray) {
            $request->validate([
                'note' => 'nullable|string|max:1000',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
            ]);
            $items = $request->items;
        } else {
            $request->validate([
                'note' => 'nullable|string|max:1000',
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);
            $items = [[
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]];
        }

        $receiptCode = (string) Str::uuid(); // ✅ gom các dòng nhập kho thành 1 phiếu
        $note = $request->note;

        DB::transaction(function () use ($items, $inventoryService, $receiptCode, $note) {
            foreach ($items as $row) {
                // InventoryService hiện tạo 1 dòng Imports + cộng tồn kho
                $product = $inventoryService->importProduct((int) $row['product_id'], (int) $row['quantity'], $note);

                // ✅ Updated: gắn receipt_code cho dòng import vừa tạo
                // Lấy bản ghi Imports mới nhất của sản phẩm đó tại transaction hiện tại
                $product->imports()->latest('id')->first()?->update([
                    'receipt_code' => $receiptCode,
                ]);
            }
        });

        return redirect()->back()->with('success', 'Nhập kho thành công');
    }
}

