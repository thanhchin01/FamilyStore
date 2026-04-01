<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Customers;
use App\Models\Products;
use App\Models\Sales;
use App\Models\Imports;
use App\Services\DebtService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Trang Dashboard chính
        return view('admin.layouts.dashboard.index');
    }

    public function inventory()
    {
        // Trang quản lý kho (Inventory)
        // Lấy danh mục để hiển thị filter hoặc dropdown
        // Lấy danh sách sản phẩm kèm theo danh mục (eager loading 'category') để tránh N+1 query
        return view('admin.layouts.inventory.index', [
            'categories' => Categories::orderBy('name')->get(),
            'products' => Products::with('category')->orderBy('id', 'desc')->get()
        ]);
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
        // return view('admin.layouts.history.index');
        // Trang Lịch sử bán hàng chi tiết
        return view('admin.layouts.history.index', [
            'categories' => Categories::orderBy('name')->get(),
            'products' => Products::all(),
            'sales' => Sales::with(['product', 'customer', 'customer.debt'])
                ->latest()
                ->limit(200)
                ->get(),
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

        // 4. Eager load quan hệ 'debt' để lấy số tiền hiển thị
        // Sắp xếp mặc định: Người nợ nhiều nhất lên đầu
        $debtors = $query->with('debt')
            ->orderByDesc('customer__debts.total_debt')
            ->paginate(10)
            ->appends($request->query()); // Giữ tham số search khi phân trang

        return view('admin.layouts.debt_list.index', compact('debtors'));
    }

    public function stock()
    {
        // ✅ Added: đổ dữ liệu sản phẩm + lịch sử nhập kho
        // Trang Lịch sử nhập kho
        return view('admin.layouts.stock_entry.index', [
            'categories' => Categories::orderBy('name')->get(),
            'products' => Products::with('category')->orderBy('name')->get(),
            'imports' => Imports::with('product')
                ->orderByDesc('id')
                ->limit(20)
                ->get(),
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
            ->paginate(10)
            ->appends($request->query());

        return view('admin.layouts.product.index', compact('products', 'categories'));
    }

    public function statistics()
    {
        return view('admin.layouts.statistics.index');
    }
}
