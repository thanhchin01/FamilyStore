<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Http\Requests\Admin\Import\StoreImportRequest;

class ImportController extends Controller
{
    public function store(StoreImportRequest $request, InventoryService $inventoryService)
    {
        // ✅ Cho phép: nếu không có items[] thì dùng 1 dòng product_id + quantity
        // Kiểm tra xem request gửi lên là mảng nhiều sản phẩm hay chỉ 1 sản phẩm lẻ
        $hasItemsArray = $request->has('items') && is_array($request->items);

        if ($hasItemsArray) {
            // Gán biến items từ mảng gửi lên
            $items = $request->items;
        } else {
            // Chuẩn hóa thành mảng để xử lý chung logic phía dưới
            $items = [[
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]];
        }

        // Tạo mã UUID duy nhất để gom tất cả các dòng nhập lần này thành 1 phiếu (Receipt)
        $receiptCode = (string) Str::uuid(); 
        $note = $request->note;

        // Sử dụng Transaction để đảm bảo toàn vẹn dữ liệu
        DB::transaction(function () use ($items, $inventoryService, $note) {
            foreach ($items as $row) {
                // InventoryService handles creating the receipt, item, movement, and updating stock
                $inventoryService->importProduct(
                    (int) $row['product_id'], 
                    (int) $row['quantity'], 
                    (int) ($row['unit_cost'] ?? 0), 
                    $note
                );
            }
        });


        return redirect()->back()->with('success', 'Nhập kho thành công');
    }
}
