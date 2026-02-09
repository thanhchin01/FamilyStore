<?php

namespace App\Services;

use App\Models\Products;
use App\Models\Imports;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Nhập kho cho sản phẩm
     *
     * @param int $productId  ID sản phẩm
     * @param int $quantity   Số lượng nhập
     * @param string|null $note Ghi chú (nếu có)
     */
    public function importProduct(int $productId, int $quantity, ?string $note = null)
    {
        return DB::transaction(function () use ($productId, $quantity, $note) {

            // 1️⃣ Lấy sản phẩm
            $product = Products::findOrFail($productId);

            // 2️⃣ Lưu lịch sử nhập kho
            Imports::create([
                'product_id'  => $product->id,
                'quantity'    => $quantity,
                'note'        => $note,
                'imported_at' => now(),
            ]);

            // 3️⃣ Cộng tồn kho
            $product->increment('stock', $quantity);

            return $product;
        });
    }
}
