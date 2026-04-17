<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Giả lập lịch sử đơn hàng
        $orders = [
            [
                'id' => '#KQ-1001',
                'date' => '2026-04-10',
                'status' => 'Đã hoàn thành',
                'total' => 12500000
            ],
            [
                'id' => '#KQ-1025',
                'date' => '2026-04-15',
                'status' => 'Đang giao hàng',
                'total' => 4500000
            ]
        ];
        
        return view('client.layouts.profile.index', compact('user', 'orders'));
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();
        $user->update($request->validated());

        return redirect()->back()->with('success', 'Cập nhật thông tin cá nhân thành công.');
    }
}
