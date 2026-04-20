<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\Products;
use App\Models\Sales;
use App\Services\Admin\SaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\Admin\Sale\StoreSaleRequest;

class SaleController extends Controller
{
    //
    protected SaleService $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function store(StoreSaleRequest $request)
    {
        $hasItemsArray = $request->has('items') && is_array($request->items);

        // Xử lý khách hàng (dùng chung cho cả 2 trường hợp)
        $customer = null;
        if ($request->customer_type === 'customer') {
            $customer = Customers::firstOrCreate(
                ['phone' => $request->phone],
                [
                    'name'          => $request->customer_name ?? '',
                    'address'       => $request->customer_address ?? null,
                    'relative_name' => $request->customer_relative_name ?? null,
                ]
            );
            $updates = [];
            if ($request->filled('customer_name')) {
                $updates['name'] = $request->customer_name;
            }
            if ($request->filled('customer_address')) {
                $updates['address'] = $request->customer_address;
            }
            if ($request->filled('customer_relative_name')) {
                $updates['relative_name'] = $request->customer_relative_name;
            }
            if (!empty($updates)) {
                $customer->update($updates);
            }
        }

        // Build danh sách items cho service
        $items = [];
        if ($hasItemsArray) {
            foreach ($request->items as $row) {
                $product = Products::findOrFail($row['product_id']);
                $items[] = [
                    'product' => $product,
                    'quantity' => (int) $row['quantity'],
                    'price' => (int) $row['price'],
                ];
            }
        } else {
            $product = Products::findOrFail($request->product_id);
            $items[] = [
                'product' => $product,
                'quantity' => (int) $request->quantity,
                'price' => (int) $request->price,
            ];
        }

        // Gọi Service bán hàng (theo hóa đơn, dù 1 hay nhiều sản phẩm)
        $this->saleService->sellInvoice(
            items: $items,
            customer: $customer,
            paidAmount: (int) ($request->paid_amount ?? 0)
        );

        return redirect()
            ->back()
            ->with('success', 'Bán hàng thành công');
    }
    // public function storeSale(Request $request)
    // {
    //     // 1. Validate tối thiểu
    //     $request->validate([
    //         'customer_name' => 'required|string|max:255',
    //         'phone'         => 'nullable|string|max:20',
    //         'total_amount'  => 'required|numeric|min:0',
    //         'paid_amount'   => 'required|numeric|min:0',
    //         'debt_amount'   => 'required|numeric|min:0',
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         /**
    //          * 2. Xử lý khách hàng
    //          * - Có SĐT → tìm khách cũ
    //          * - Không có hoặc không tồn tại → tạo mới
    //          */
    //         $customer = null;

    //         if ($request->phone) {
    //             $customer = Customers::where('phone', $request->phone)->first();
    //         }

    //         if (!$customer) {
    //             $customer = Customers::create([
    //                 'name'  => $request->customer_name,
    //                 'phone' => $request->phone,
    //                 'debt'  => 0,
    //             ]);
    //         } else {
    //             // cập nhật tên phòng trường hợp sửa lại
    //             $customer->name = $request->customer_name;
    //             $customer->save();
    //         }

    //         /**
    //          * 3. Lưu bảng sales
    //          */
    //         Sales::create([
    //             'customer_id'  => $customer->id,
    //             'total_amount' => $request->total_amount,
    //             'paid_amount'  => $request->paid_amount,
    //             'debt_amount'  => $request->debt_amount,
    //         ]);

    //         /**
    //          * 4. Nếu còn nợ → cộng vào khách
    //          */
    //         if ($request->debt_amount > 0) {
    //             $customer->increment('debt', $request->debt_amount);
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Hoàn tất thanh toán thành công'
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Có lỗi xảy ra',
    //             'error'   => $e->getMessage()
    //         ], 500);
    //     }
    // }
}
