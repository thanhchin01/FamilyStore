<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Wishlist::with('product.category')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('client.layouts.wishlist.index', compact('wishlistItems'));
    }

    public function toggle(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập để thực hiện.'], 401);
        }

        $productId = $request->product_id;
        $userId = Auth::id();

        $exists = Wishlist::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($exists) {
            $exists->delete();
            return response()->json(['success' => true, 'action' => 'removed', 'message' => 'Đã xóa khỏi danh sách yêu thích.']);
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
            return response()->json(['success' => true, 'action' => 'added', 'message' => 'Đã thêm vào danh sách yêu thích.']);
        }
    }
}
