<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Categories;
use App\Models\Customers;
use App\Models\Products;
use App\Models\SaleInvoice;
use App\Models\ImportReceipt;
use App\Models\CustomerDebtBalance;
use App\Models\DebtTransaction;
use App\Models\Order;
use App\Services\Admin\DebtService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalRevenue = SaleInvoice::sum('grand_total');
        $newOrdersCount = Order::whereDate('created_at', '>=', now()->subDays(7))->count();
        $newCustomersCount = Customers::whereDate('created_at', '>=', now()->subDays(30))->count();
        $lowStockProductsCount = Products::where('is_active', true)
            ->where('stock', '<=', 5)
            ->count();

        $latestOrders = Order::with(['customer', 'items.product'])
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.layouts.dashboard.index', compact(
            'totalRevenue',
            'newOrdersCount',
            'newCustomersCount',
            'lowStockProductsCount',
            'latestOrders'
        ));
    }


    public function inventory()
    {
        // Lấy tất cả sản phẩm kèm danh mục để hiển thị trong kho
        $products = Products::with('category')->orderBy('name')->get();
        $categories = Categories::orderBy('name')->get();

        return view('admin.layouts.inventory.index', compact('products', 'categories'));
    }

    public function sale()
    {
        // Trang Bán hàng (POS)
        return view('admin.layouts.sale.index', [
            'categories' => Categories::orderBy('name')->get(),
            'products' => Products::select('id', 'name', 'price', 'stock', 'model', 'category_id')->get(),
            // Lấy 100 đơn hàng gần nhất (POS)
            'sales' => SaleInvoice::with(['items', 'customer.debtBalance'])
                ->where('channel', 'pos')
                ->orderByDesc('id')
                ->limit(100)
                ->get(),
        ]);
    }


    /**
     * Tìm khách theo SĐT (trả JSON cho AJAX).
     * Trả về thông tin khách + tổng nợ hiện tại (để hiển thị và cộng dồn).
     */
    public function findCustomerByPhone(Request $request)
    {
        $phone = $request->get('phone');
        
        if (empty($phone)) {
            return response()->json(['found' => false]);
        }

        // Tìm khách trong DB, kèm thông tin nợ ('debtBalance')
        $customer = Customers::with('debtBalance')->where('phone', $phone)->first();
        if (!$customer) {
            return response()->json(['found' => false]);
        }

        // Tính tổng nợ: nếu có bản ghi nợ thì lấy balance_amount, ngược lại là 0
        $totalDebt = $customer->debtBalance ? (int) $customer->debtBalance->balance_amount : 0;

        return response()->json([
            'found' => true,
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'address' => $customer->address ?? '',
                'relative_name' => $customer->relative_name ?? '',
            ],
            'total_debt' => $totalDebt,
        ]);
    }


    /**
     * Ghi nhận khách trả nợ (từ modal xem chi tiết hoặc trang nợ).
     */
    public function payDebt(Request $request, DebtService $debtService)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|integer|min:1',
        ]);

        $customer = Customers::findOrFail($request->customer_id);
        $debt = $customer->debtBalance;
        
        if (!$debt || $debt->balance_amount <= 0) {
            return redirect()->back()->with('error', 'Khách hàng không còn nợ.');
        }

        $amount = min((int) $request->amount, (int) $debt->balance_amount);
        
        $debtService->payDebt(
            $customer, 
            $amount, 
            $request->get('description'),
            $request->get('sale_id')
        );

        return redirect()->back()->with('success', 'Đã ghi nhận trả nợ ' . number_format($amount) . 'đ.');
    }


    public function history()
    {
        return view('admin.layouts.history.index', [
            'categories' => Categories::orderBy('name')->get(),
            'products' => Products::all(),
            'sales' => SaleInvoice::with(['customer.debtBalance'])
                ->latest()
                ->paginate(15),
        ]);
    }


    public function debt(Request $request)
    {
        // 1. Khởi tạo query từ model Customers
        $query = Customers::query();

        // 2. Chỉ lấy khách đang có nợ (balance_amount > 0)
        $query->join('customer_debt_balances', 'customers.id', '=', 'customer_debt_balances.customer_id')
            ->where('customer_debt_balances.balance_amount', '>', 0)
            ->select('customers.*');

        // 3. Xử lý tìm kiếm (nếu có từ khóa)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('customers.name', 'like', "%{$keyword}%")
                    ->orWhere('customers.phone', 'like', "%{$keyword}%");
            });
        }

        // 4. Eager load quan hệ 'debtBalance'
        $debtors = $query->with('debtBalance')
            ->orderByDesc('customer_debt_balances.balance_amount')
            ->paginate(15)
            ->appends($request->query());

        // 5. Lấy chi tiết giao dịch cho khách hàng đang được chọn
        $selectedId = $request->selected_id;
        $selectedCustomer = null;
        $history = collect();

        if ($selectedId || $debtors->count() > 0) {
            $selectedCustomer = $selectedId 
                ? Customers::with('debtBalance')->find($selectedId) 
                : $debtors->first();

            if ($selectedCustomer) {
                // 1. Lấy lịch sử giao dịch công nợ
                $transactions = DebtTransaction::where('customer_id', $selectedCustomer->id)
                    ->orderByDesc('occurred_at')
                    ->get();

                $history = $transactions->map(function($t) {
                    return (object)[
                        'type' => $t->type, // 'purchase', 'payment', 'adjustment'
                        'amount' => $t->amount,
                        'description' => $t->description,
                        'occurred_at' => $t->occurred_at,
                        'invoice_code' => $t->saleInvoice?->invoice_code
                    ];
                });
            }
        }

        return view('admin.layouts.debt_list.index', compact('debtors', 'selectedCustomer', 'history'));
    }


    public function stock()
    {
        // Trang Nhập kho
        return view('admin.layouts.stock_entry.index', [
            'categories' => Categories::orderBy('name')->get(),
            'products' => Products::with('category')->orderBy('name')->get(),
            // Lấy các bản ghi biến động kho loại 'import' (nhập) để hiển thị lịch sử
            'imports' => \App\Models\InventoryMovement::with('product')
                ->where('movement_type', 'import')
                ->latest()
                ->paginate(15),

        ]);

    }


    public function products(Request $request)
    {
        // Danh sách danh mục dùng cho bộ lọc & modal
        $categories = Categories::orderBy('name')->get();

        // Khởi tạo query sản phẩm
        // Eager load 'category' để hiển thị tên danh mục
        $query = Products::with('category');

        // Lọc theo danh mục (category_id trong DB)
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo hãng
        if ($request->filled('brand')) {
            $query->where('brand', 'like', '%' . $request->brand . '%');
        }

        // Tìm kiếm theo tên sản phẩm
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        // Sắp xếp & phân trang, giữ nguyên query string khi chuyển trang
        // orderByDesc('id'): Sản phẩm mới nhất lên đầu
        $products = $query
            ->orderByDesc('id')
            ->paginate(15)
            ->appends($request->query());

        return view('admin.layouts.product.index', compact('products', 'categories'));
    }

    public function statistics()
    {
        // 1. Dữ liệu doanh thu 7 ngày gần nhất cho biểu đồ
        $revenueData = [];
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d/m');
            $dailySum = SaleInvoice::whereDate('sold_at', $date->toDateString())
                ->sum('grand_total');
            $revenueData[] = $dailySum;
        }

        // 2. Tỷ trọng ngành hàng (Top 5 danh mục)
        $categoriesStats = Categories::withCount('products')
            ->orderByDesc('products_count')
            ->limit(5)
            ->get();

        // 3. Các chỉ số tổng hợp
        $stats = [
            'today_revenue' => SaleInvoice::whereDate('sold_at', now()->toDateString())->sum('grand_total'),
            'new_orders_month' => SaleInvoice::whereMonth('sold_at', now()->month)->count(),
            'total_customers' => Customers::count(),
            'new_debt_month' => SaleInvoice::whereMonth('sold_at', now()->month)->sum('debt_amount'),
        ];

        return view('admin.layouts.statistics.index', compact('labels', 'revenueData', 'categoriesStats', 'stats'));
    }


    /**
     * Lấy số lượng thông báo mới (Đơn hàng & Tin nhắn)
     */
    public function getNotificationCounts(Request $request)
    {
        $orderCount = \App\Models\Order::where('status', 'pending')->count();
        
        $messageCount = \App\Models\Message::where('is_read', false)->count();


        $data = [
            'success' => true,
            'orders' => $orderCount,
            'messages' => $messageCount
        ];

        // Nếu yêu cầu lấy danh sách khách nợ (Dùng cho trang POS)
        if ($request->get('type') === 'debtors') {
            $data['debtors'] = Customers::join('customer_debt_balances', 'customers.id', '=', 'customer_debt_balances.customer_id')
                ->where('customer_debt_balances.balance_amount', '>', 0)
                ->select('customers.*', 'customer_debt_balances.balance_amount as total_debt')
                ->get();
        }


        return response()->json($data);
    }

    /**
     * Trang Quản lý Đơn hàng (Orders)
     */
    public function orders()
    {
        $orders = \App\Models\Order::with(['customer', 'items'])
            ->orderByDesc('id')
            ->paginate(15);

        return view('admin.layouts.order.index', compact('orders'));
    }
}
