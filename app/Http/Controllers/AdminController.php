<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Products;
use App\Models\Sales;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.layouts.dashboard.index');
    }

    public function inventory()
    {
        return view('admin.layouts.inventory.index');
    }

    public function sale()
    {
        // $products = Products::all();
        // $sales = Sales::latest()->limit(20)->get();
        // return view('admin.layouts.sale.index', compact('products', 'sales'));
        return view('admin.layouts.sale.index', [
            'categories' => Categories::orderBy('name')->get(),
            'products'   => Products::all(), // dùng cho JS filter
            'sales'      => Sales::with(['product', 'customer'])
                ->latest()
                ->get(),
        ]);
    }

    public function history()
    {
        return view('admin.layouts.history.index');
    }

    public function debt()
    {
        return view('admin.layouts.debt_list.index');
    }

    public function stock()
    {
        return view('admin.layouts.stock_entry.index');
    }

    public function products(Request $request)
    {
        $query = Products::query();
        $categories = Categories::orderBy('name')->get();
        $products = Products::orderByDesc('id')->paginate(10);
        // Lọc theo danh mục
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Lọc theo hãng
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // Tìm kiếm tên
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $products = $query
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.layouts.product.index', compact('products', 'categories'));
        // $products = Products::orderByDesc('id')->paginate(10);
        // return view('admin.layouts.product.index', compact('products'));
    }

    public function statistics()
    {
        return view('admin.layouts.statistics.index');
    }
}
