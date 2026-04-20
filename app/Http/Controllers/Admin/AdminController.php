<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Categories;
use App\Models\Customers;
use App\Models\Products;
use App\Models\Sales;
use App\Models\Imports;
use App\Services\Admin\DebtService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Tính toán các con số thống kê thực tế
        $totalProducts = \App\Models\Products::count();
        $totalSalesCount = \App\Models\Sales::count();
        $totalRevenue = \App\Models\Sales::sum(\DB::raw('price * quantity'));
        $totalCustomerDebt = \App\Models\Customer_Debts::sum('total_debt');
        
        // 2. Lấy 5 đơn hàng mới nhất (Sales)
        $recentSales = \App\Models\Sales::with(['product', 'customer'])
            ->latest()
            ->limit(5)
            ->get();

        // 3. Lấy 5 khách hàng nợ nhiều nhất
        $topDebtors = \App\Models\Customers::join('customer__debts', 'customers.id', '=', 'customer__debts.customer_id')
            ->where('customer__debts.total_debt', '>', 0)
            ->orderByDesc('customer__debts.total_debt')
            ->select('customers.*', 'customer__debts.total_debt')
            ->limit(5)
            ->get();

        return view('admin.layouts.dashboard.index', compact(
            'totalProducts', 
            'totalSalesCount', 
            'totalRevenue', 
            'totalCustomerDebt',
            'recentSales',
            'topDebtors'
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
        // $products = Products::all();
        // $sales = Sales::latest()->limit(20)->get();
        // return view('admin.layouts.sale.index', compact('products', 'sales'));
        // ✅ Updated: sales history cần đọc thêm customer.debt cho modal & trạng thái nợ
        
        // Trang Bán hàng (POS)
        return view('admin.layouts.sale.index', [
            'categories' => Categories::orderBy('name')->get(),
            // Tối ưu: Chỉ lấy cột cần thiết để giảm tải, tránh lấy description dài
            // Dữ liệu này sẽ được bắn sang JavaScript để xử lý chọn sản phẩm nhanh
            'products' => Products::select('id', 'name', 'price', 'stock', 'model', 'category_id')->get(),
            // ✅ Updated: gom theo invoice_code sẽ xử lý ở view (groupBy)
            // Lấy 100 đơn hàng gần nhất, kèm thông tin sản phẩm, khách hàng và nợ của khách đó
            'sales' => Sales::with(['product', 'customer', 'customer.debt'])
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
        
        // Nếu không có SĐT gửi lên -> trả về không tìm thấy
        if (empty($phone)) {
            return response()->json(['found' => false]);
        }

        // Tìm khách trong DB, kèm thông tin nợ ('debt')
        $customer = Customers::with('debt')->where('phone', $phone)->first();
        if (!$customer) {
            return response()->json(['found' => false]);
        }

        // Tính tổng nợ: nếu có bản ghi nợ thì lấy total_debt, ngược lại là 0
        $totalDebt = $customer->debt ? (int) $customer->debt->total_debt : 0;

        // Trả về JSON để JavaScript điền vào form bán hàng
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
        // Validate dữ liệu đầu vào: phải có ID khách và số tiền > 0
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|integer|min:1',
        ]);

        $customer = Customers::findOrFail($request->customer_id);
        $debt = $customer->debt;
        
        // Kiểm tra logic: Khách không nợ thì không thể trả
        if (!$debt || $debt->total_debt <= 0) {
            return redirect()->back()->with('error', 'Khách hàng không còn nợ.');
        }

        // Đảm bảo số tiền trả không lớn hơn số tiền đang nợ
        $amount = min((int) $request->amount, (int) $debt->total_debt);
        
        // Gọi Service để xử lý trừ nợ và ghi log giao dịch
        $debtService->payDebt($customer, $amount, $request->get('description'));

        return redirect()->back()->with('success', 'Đã ghi nhận trả nợ ' . number_format($amount) . 'đ.');
    }

    public function history()
    {
        return view('admin.layouts.history.index', [
            'categories' => Categories::orderBy('name')->get(),
            'products' => Products::all(),
            'sales' => Sales::with(['product', 'customer', 'customer.debt'])
                ->latest()
                ->paginate(15),
        ]);
    }

    public function debt(Request $request)
    {
        //  return view('admin.layouts.debt_list.index');
        // 1. Khởi tạo query từ model Customers
        $query = Customers::query();

        // 2. Chỉ lấy khách đang có nợ (total_debt > 0)
        // Sử dụng join để sắp xếp theo số tiền nợ giảm dần
        $query->join('customer__debts', 'customers.id', '=', 'customer__debts.customer_id')
            ->where('customer__debts.total_debt', '>', 0)
            ->select('customers.*'); // Chỉ lấy các cột của bảng customer

        // 3. Xử lý tìm kiếm (nếu có từ khóa)
        // Cho phép tìm theo tên hoặc số điện thoại khách hàng
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('customers.name', 'like', "%{$keyword}%")
                    ->orWhere('customers.phone', 'like', "%{$keyword}%");
            });
        }

        // 4. Eager load quan hệ 'debt'
        $debtors = $query->with('debt')
            ->orderByDesc('customer__debts.total_debt')
            ->paginate(15)
            ->appends($request->query());

        // 5. Lấy chi tiết giao dịch cho khách hàng đang được chọn
        $selectedId = $request->selected_id;
        $selectedCustomer = null;
        $history = collect();

        if ($selectedId || $debtors->count() > 0) {
            $selectedCustomer = $selectedId 
                ? Customers::with('debt')->find($selectedId) 
                : $debtors->first();

            if ($selectedCustomer) {
                // 1. Lấy các lần TRẢ NỢ (payment) và GHI NỢ LẺ (không qua hóa đơn - nếu có)
                $transactions = \App\Models\Debt_Transactions::where('customer_id', $selectedCustomer->id)
                    ->where(function($q) {
                        $q->where('type', 'payment')
                          ->orWhereNull('sale_id'); // Những lần ghi nợ bằng tay không qua bán hàng
                    })
                    ->orderByDesc('created_at')
                    ->get();

                $transactionHistory = $transactions->map(function($t) {
                    return (object)[
                        'type' => $t->type, 
                        'amount' => $t->amount,
                        'description' => $t->description,
                        'occurred_at' => $t->created_at,
                        'invoice_code' => null
                    ];
                });

                // 2. Lấy các lần MUA HÀNG từ bảng sales
                $salesHistory = \App\Models\Sales::with('product')
                    ->where('customer_id', $selectedCustomer->id)
                    ->orderByDesc('sold_at')
                    ->get();

                // Gộp chung và sắp xếp
                $history = $transactionHistory->concat($salesHistory->map(function($sale) {
                    return (object)[
                        'type' => 'purchase',
                        'amount' => $sale->price * $sale->quantity,
                        'description' => $sale->product->name . ' (SL: ' . $sale->quantity . ')',
                        'occurred_at' => $sale->sold_at,
                        'invoice_code' => $sale->invoice_code
                    ];
                }))->sortByDesc('occurred_at');
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
            'imports' => Imports::with('product')
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
            $dailySum = \App\Models\Sales::whereDate('created_at', $date->toDateString())
                ->sum(\DB::raw('price * quantity'));
            $revenueData[] = $dailySum;
        }

        // 2. Tỷ trọng ngành hàng (Top 5 danh mục)
        $categoriesStats = Categories::withCount('products')
            ->orderByDesc('products_count')
            ->limit(5)
            ->get();

        // 3. Các chỉ số tổng hợp
        $stats = [
            'today_revenue' => \App\Models\Sales::whereDate('created_at', now()->toDateString())->sum(\DB::raw('price * quantity')),
            'new_orders_month' => \App\Models\Sales::whereMonth('created_at', now()->month)->count(),
            'total_customers' => \App\Models\Customers::count(),
            'new_debt_month' => \App\Models\Sales::whereMonth('created_at', now()->month)->sum(\DB::raw('price * quantity - IFNULL(paid_amount, 0)')),
        ];

        return view('admin.layouts.statistics.index', compact('labels', 'revenueData', 'categoriesStats', 'stats'));
    }

    /**
     * Lấy số lượng thông báo mới (Đơn hàng & Tin nhắn)
     */
    public function getNotificationCounts(Request $request)
    {
        $orderCount = \App\Models\Order::where('status', 'pending')->count();
        
        $messageCount = \App\Models\Message::where('is_read', false)
            ->whereHas('sender', function($q) {
                $q->where('role', '!=', 'admin');
            })->count();

        $data = [
            'success' => true,
            'orders' => $orderCount,
            'messages' => $messageCount
        ];

        // Nếu yêu cầu lấy danh sách khách nợ (Dùng cho trang POS)
        if ($request->get('type') === 'debtors') {
            $data['debtors'] = Customers::join('customer__debts', 'customers.id', '=', 'customer__debts.customer_id')
                ->where('customer__debts.total_debt', '>', 0)
                ->select('customers.*', 'customer__debts.total_debt')
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
