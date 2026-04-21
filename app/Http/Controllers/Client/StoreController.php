<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\Client\CartService;
use App\Services\Client\OrderService;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Categories;

class StoreController extends Controller
{
    protected $cartService;
    protected $orderService;
    protected $paymentService;

    public function __construct(CartService $cartService, OrderService $orderService, \App\Services\Client\PaymentService $paymentService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
        $this->paymentService = $paymentService;
    }

    public function home()
    {
        $products = Products::where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        return view('client.layouts.home.index', compact('products'));
    }

    public function productDetail($slug)
    {
        $product = Products::with(['category', 'productImages'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('client.layouts.product.show', compact('product'));
    }

    public function cart()
    {
        $cartItems = $this->cartService->getCart();
        $total = $this->cartService->subtotal();

        return view('client.layouts.cart.index', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        $cart = $this->cartService->add($productId, $quantity);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Sản phẩm đã được thêm vào giỏ hàng!',
                'cart_count' => count($cart)
            ]);
        }

        return redirect()->back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng!');
    }

    public function updateCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $cart = $this->cartService->update($productId, $quantity);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật số lượng thành công!',
                'cart_count' => count($cart),
                'subtotal' => number_format($this->cartService->subtotal()) . 'đ',
                'item_total' => isset($cart[$productId]) ? number_format($cart[$productId]['price'] * $cart[$productId]['quantity']) . 'đ' : 0
            ]);
        }

        return redirect()->back();
    }

    public function removeFromCart(Request $request)
    {
        $productId = $request->input('product_id');
        $cart = $this->cartService->remove($productId);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng!',
                'cart_count' => count($cart),
                'subtotal' => number_format($this->cartService->subtotal()) . 'đ'
            ]);
        }

        return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    public function products(Request $request)
    {
        $query = Products::query()->where('is_active', true);

        // Search by name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by brand
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // Filter by price range
        if ($request->filled('price_range')) {
            switch ($request->price_range) {
                case '1': $query->where('price', '<', 5000000); break;
                case '2': $query->whereBetween('price', [5000000, 15000000]); break;
                case '3': $query->whereBetween('price', [15000000, 30000000]); break;
                case '4': $query->where('price', '>', 30000000); break;
            }
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_asc': $query->orderBy('price', 'asc'); break;
            case 'price_desc': $query->orderBy('price', 'desc'); break;
            default: $query->orderBy('created_at', 'desc'); break;
        }

        $products = $query->paginate(12);
        $categories = Categories::where('is_active', true)->get();
        $brands = Products::select('brand')->distinct()->whereNotNull('brand')->pluck('brand');

        return view('client.layouts.product.index', compact('products', 'categories', 'brands'));
    }

    public function checkout()
    {
        $cartItems = $this->cartService->getCart();
        if (empty($cartItems)) {
            return redirect()->route('client.products.index')->with('info', 'Giỏ hàng của bạn đang trống.');
        }

        $total = $this->cartService->subtotal();

        return view('client.layouts.checkout.index', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    /**
     * Xử lý đặt hàng (Place Order)
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'shipping_name'    => 'required|string|max:255',
            'shipping_phone'   => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'payment_method'   => 'required|in:cod,transfer',
        ]);

        $cartItems = $this->cartService->getCart();
        if (empty($cartItems)) {
            return redirect()->route('client.products.index')->with('error', 'Giỏ hàng trống.');
        }

        $subtotal = $this->cartService->subtotal();
        
        $orderData = [
            'customer_id'      => null, // Có thể mở rộng nếu có bảng Customers riêng
            'subtotal'         => $subtotal,
            'shipping_fee'     => 0, // Mặc định freeship
            'grand_total'      => $subtotal,
            'payment_method'   => $request->payment_method,
            'shipping_name'    => $request->shipping_name,
            'shipping_phone'   => $request->shipping_phone,
            'shipping_address' => $request->shipping_address,
            'note'             => $request->note,
            'items'            => array_map(function($item) {
                return [
                    'product_id' => $item['id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price']
                ];
            }, array_values($cartItems))
        ];

        try {
            $order = $this->orderService->createOrder($orderData);
            
            // Khởi tạo bản ghi thanh toán
            $this->paymentService->initializePayment($order, $request->payment_method);

            // Xóa giỏ hàng
            $this->cartService->clear();

            return redirect()->route('client.home')->with('success', 'Đơn hàng #' . $order->order_code . ' đã được đặt thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }
}
