<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Danh sách người dùng
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                  ->orWhere('email', 'like', "%$keyword%")
                  ->orWhere('phone', 'like', "%$keyword%")
                  ->orWhere('username', 'like', "%$keyword%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderByDesc('created_at')->paginate(15);

        return view('admin.layouts.user.index', compact('users'));
    }

    /**
     * Xem chi tiết người dùng (Thường trả về JSON cho Modal)
     */
    public function show($id)
    {
        $user = User::withCount(['orders', 'reviews'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    /**
     * Bật/Tắt trạng thái tài khoản
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        
        $newStatus = $user->status === 'active' ? 'blocked' : 'active';
        $user->update(['status' => $newStatus]);

        return redirect()->back()->with('success', 'Đã ' . ($newStatus === 'active' ? 'mở khóa' : 'khóa') . ' tài khoản người dùng.');
    }

    /**
     * Xóa tài khoản người dùng
     */
    public function destroy($id)
    {
        $user = User::withCount('orders')->findOrFail($id);

        if ($user->orders_count > 0) {
            return redirect()->back()->with('error', 'Không thể xóa người dùng đã có lịch sử đơn hàng. Hãy sử dụng tính năng Khóa tài khoản.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Đã xóa tài khoản người dùng thành công.');
    }
}

