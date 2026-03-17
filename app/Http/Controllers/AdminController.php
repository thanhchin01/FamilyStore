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
        return view('admin.layouts.dashboard.index');
    }

    public function inventory()
    {
        return view('admin.layouts.inventory.index', [
            'categories' => Categories::orderBy('name')->get(),
            'products'   => Products::with('category')->orderBy('id', 'desc')->get()
        ]);
    }

    public function sale()
    {
        // $products = Products::all();
        // $sales = Sales::latest()->limit(20)->get();
        // return view('admin.layouts.sale.index', compact('products', 'sales'));
        // ✅ Updated: sales history cần đọc thêm customer.debt cho modal & trạng thái nợ
        return view('admin.layouts.sale.index', [
            'categories' => Categories::orderBy('name')->get(),
            'products'   => Products::all(),
            // ✅ Updated: gom theo invoice_code sẽ xử lý ở view (groupBy)
            'sales'      => Sales::with(['product', 'customer', 'customer.debt'])
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

        $customer = Customers::with('debt')->where('phone', $phone)->first();
        if (!$customer) {
            return response()->json(['found' => false]);
        }

        $totalDebt = $customer->debt ? (int) $customer->debt->total_debt : 0;

        return response()->json([
            'found'       => true,
            'customer'    => [
                'id'            => $customer->id,
                'name'          => $customer->name,
                'phone'         => $customer->phone,
                'address'       => $customer->address ?? '',
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
            'amount'       => 'required|integer|min:1',
        ]);

        $customer = Customers::findOrFail($request->customer_id);
        $debt = $customer->debt;
        if (!$debt || $debt->total_debt <= 0) {
            return redirect()->back()->with('error', 'Khách hàng không còn nợ.');
        }

        $amount = min((int) $request->amount, (int) $debt->total_debt);
        $debtService->payDebt($customer, $amount, $request->get('description'));

        return redirect()->back()->with('success', 'Đã ghi nhận trả nợ ' . number_format($amount) . 'đ.');
    }

    public function history()
    {
        // return view('admin.layouts.history.index');
         return view('admin.layouts.history.index', [
            'categories' => Categories::orderBy('name')->get(),
            'products'   => Products::all(),
            'sales'      => Sales::with(['product', 'customer', 'customer.debt'])
                ->latest()
                ->limit(200)
                ->get(),
        ]);
    }

    public function debt()
    {
        return view('admin.layouts.debt_list.index');
    }

    public function stock()
    {
        // ✅ Added: đổ dữ liệu sản phẩm + lịch sử nhập kho
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
