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
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Cập nhật thông tin cơ bản
        $user->update($request->only(['name', 'email', 'phone', 'address']));

        // Xử lý thông tin Profile (Khách hàng)
        $customerData = $request->only(['gender', 'birthday']);

        // Xử lý Upload Avatar
        if ($request->hasFile('avatar')) {
            $customer = $user->customerProfile;
            
            // Xóa ảnh cũ nếu có
            if ($customer && $customer->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($customer->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($customer->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $customerData['avatar'] = $path;
        }

        $user->customerProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $customerData
        );

        return redirect()->back()->with('success', 'Cập nhật thông tin cá nhân thành công.');
    }
}


