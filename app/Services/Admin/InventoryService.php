<?php

namespace App\Services\Admin;

use App\Models\Products;
use App\Models\ImportReceipt;
use App\Models\ImportItem;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InventoryService
{
    /**
     * Nhập kho cho sản phẩm
     *
     * @param int $productId  ID sản phẩm
     * @param int $quantity   Số lượng nhập
     * @param int|null $unitCost Giá nhập mỗi đơn vị (nếu có)
     * @param string|null $note Ghi chú (nếu có)
     * @param string|null $supplierName Tên nhà cung cấp
     */
    public function importProduct(int $productId, int $quantity, ?int $unitCost = 0, ?string $note = null, ?string $supplierName = null)
    {
        return DB::transaction(function () use ($productId, $quantity, $unitCost, $note, $supplierName) {

            // 1️⃣ Lấy sản phẩm
            $product = Products::findOrFail($productId);

            // 2️⃣ Tạo phiếu nhập kho (Header)
            $receipt = ImportReceipt::create([
                'receipt_code' => 'IMP-' . strtoupper(Str::random(8)),
                'created_by' => auth('admin')->id(),
                'supplier_name' => $supplierName,
                'note' => $note,
                'received_at' => now(),
            ]);

            // 3️⃣ Lưu chi tiết sản phẩm nhập
            $importItem = ImportItem::create([
                'import_receipt_id' => $receipt->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_cost' => $unitCost ?? 0,
                'line_cost' => ($unitCost ?? 0) * $quantity,
                'note' => $note,
            ]);

            // 4️⃣ Ghi nhận biến động kho
            InventoryMovement::create([
                'product_id' => $product->id,
                'movement_type' => 'import',

                'quantity_delta' => $quantity,
                'reference_type' => 'import',
                'reference_id' => $receipt->id,
                'note' => 'Nhập kho từ phiếu ' . $receipt->receipt_code,
                'occurred_at' => now(),
                'created_by' => auth('admin')->id(),
            ]);


            // 5️⃣ Cộng tồn kho
            $product->increment('stock', $quantity);

            return $product;
        });
    }
}

