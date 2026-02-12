<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\Products;
use App\Models\Sales;
use App\Services\SaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    //
    protected SaleService $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'   => 'required|exists:products,id',
            'quantity'     => 'required|integer|min:1',
            'price'        => 'required|integer|min:0',
            'customer_type' => 'required|in:guest,customer',
            'paid_amount'  => 'nullable|integer|min:0',
        ]);

        $product = Products::findOrFail($request->product_id);

        // Xử lý khách hàng
        $customer = null;

        if ($request->customer_type === 'customer') {
            $customer = Customers::firstOrCreate(
                ['phone' => $request->phone],
                ['name' => $request->customer_name]
            );
        }

        // Gọi Service bán hàng
        $this->saleService->sell(
            product: $product,
            quantity: $request->quantity,
            price: $request->price,
            customer: $customer,
            paidAmount: $request->paid_amount ?? 0
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
