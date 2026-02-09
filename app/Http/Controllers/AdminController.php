<?php

namespace App\Http\Controllers;

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
        return view('admin.layouts.sale.index');
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

    public function products()
    {
        return view('admin.layouts.product.index');
    }

    public function statistics()
    {
        return view('admin.layouts.statistics.index');
    }
}
