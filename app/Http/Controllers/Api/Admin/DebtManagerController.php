<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\Debt_Transactions;
use App\Services\Admin\DebtService;
use Illuminate\Http\Request;

class DebtManagerController extends Controller
{
    protected $debtService;

    public function __construct(DebtService $debtService)
    {
        $this->debtService = $debtService;
    }

    /**
     * Danh sách khách hàng và tình trạng nợ
     */
    public function index(Request $request)
    {
        $customers = Customers::with('debt')
            ->when($request->search, function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('phone', 'LIKE', '%' . $request->search . '%');
            })
            ->when($request->has_debt, function($q) {
                $q->whereHas('debt', function($sq) {
                    $sq->where('total_debt', '>', 0);
                });
            })
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $customers
        ]);
    }

    /**
     * Chi tiết lịch sử nợ của 1 khách hàng
     */
    public function history($customerId)
    {
        $history = Debt_Transactions::where('customer_id', $customerId)
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => $history
        ]);
    }

    /**
     * Ghi nhận khách trả nợ nhanh
     */
    public function recordPayment(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount'      => 'required|numeric|min:1',
            'note'        => 'nullable|string'
        ]);

        try {
            $customer = Customers::findOrFail($request->customer_id);
            $this->debtService->payDebt($customer, $request->amount, $request->note ?? 'Trả nợ qua Mobile App');

            return response()->json([
                'success' => true,
                'message' => 'Đã ghi nhận thanh toán nợ'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}
