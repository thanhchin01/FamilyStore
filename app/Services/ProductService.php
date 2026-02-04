<?php

namespace App\Services;

use App\Models\Products;
use App\Models\Imports;

class ProductService
{
    /**
     * Nhập hàng cho sản phẩm
     */
    public function importProduct(
        Products $product,
        int $quantity,
        ?string $note = null,
        ?string $importedAt = null
    ) {
        // 1. Tạo bản ghi nhập hàng
        Imports::create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'note' => $note,
            'imported_at' => $importedAt ?? now(),
        ]);

        // 2. Cộng tồn kho
        $product->increment('stock', $quantity);
    }
}
