<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\InventoryService;
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

        // Sử dụng Transaction để đảm bảo toàn vẹn dữ liệu: 
        // Hoặc là nhập thành công tất cả, hoặc nếu lỗi thì rollback không nhập dòng nào.
        DB::transaction(function () use ($items, $inventoryService, $receiptCode, $note) {
            foreach ($items as $row) {
                // InventoryService hiện tạo 1 dòng Imports + cộng tồn kho
                // Hàm này sẽ tăng số lượng trong bảng products và tạo record trong bảng imports
                $product = $inventoryService->importProduct((int) $row['product_id'], (int) $row['quantity'], $note);

                // ✅ Updated: gắn receipt_code cho dòng import vừa tạo
                // Logic ở đây là: Lấy danh sách imports của sản phẩm đó, sắp xếp mới nhất, lấy dòng đầu tiên
                // Vì ta vừa gọi importProduct ở trên nên dòng mới nhất chính là dòng vừa tạo.
                // Sau đó update cột receipt_code cho dòng đó.
                $product->imports()->latest('id')->first()?->update([
                    'receipt_code' => $receiptCode,
                ]);
            }
        });

        return redirect()->back()->with('success', 'Nhập kho thành công');
    }
}
