<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function home()
    {
        $products = [
            [
                'id' => 1,
                'name' => 'Máy giặt Panasonic Inverter 10kg',
                'price' => 12500000,
                'image' => 'https://images.unsplash.com/photo-1545173168-9f1947eebb7f?q=80&w=1000&auto=format&fit=crop',
                'slug' => 'may-giat-panasonic-inverter-10kg',
                'description' => 'Máy giặt Panasonic với công nghệ Inverter tiết kiệm điện năng và bảo vệ sợi vải.'
            ],
            [
                'id' => 2,
                'name' => 'Tủ lạnh Samsung Bespoke 400L',
                'price' => 25900000,
                'image' => 'https://images.unsplash.com/photo-1571175488180-ef9b64261bd0?q=80&w=1000&auto=format&fit=crop',
                'slug' => 'tu-lanh-samsung-bespoke-400l',
                'description' => 'Tủ lạnh thiết kế tinh tế, sang trọng với các ngăn linh hoạt.'
            ],
            [
                'id' => 3,
                'name' => 'Nồi chiên không dầu Philips XXL',
                'price' => 4500000,
                'image' => 'https://images.unsplash.com/photo-1584286595398-a59f21d313f5?q=80&w=1000&auto=format&fit=crop',
                'slug' => 'noi-chien-khong-dau-philips-xxl',
                'description' => 'Nồi chiên không dầu công suất lớn, giảm 80% dầu mỡ dư thừa.'
            ],
            [
                'id' => 4,
                'name' => 'Quạt cây điều khiển từ xa Hatari',
                'price' => 1200000,
                'image' => 'https://images.unsplash.com/photo-1585338107529-13afc5f02586?q=80&w=1000&auto=format&fit=crop',
                'slug' => 'quat-cay-hatari',
                'description' => 'Quạt cây nhập khẩu Thái Lan, siêu bền và êm ái.'
            ],
            [
                'id' => 5,
                'name' => 'Smart TV LG Oled C3 55 inch',
                'price' => 32000000,
                'image' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?q=80&w=1000&auto=format&fit=crop',
                'slug' => 'smart-tv-lg-oled-c3',
                'description' => 'Trải nghiệm hình ảnh chân thực với công nghệ OLED của LG.'
            ],
            [
                'id' => 6,
                'name' => 'Máy hút bụi không dây Dyson V15',
                'price' => 18900000,
                'image' => 'https://images.unsplash.com/photo-1527515637462-cff94eecc1ac?q=80&w=1000&auto=format&fit=crop',
                'slug' => 'may-hut-bui-dyson-v15',
                'description' => 'Máy hút bụi mạnh mẽ nhất của Dyson với công nghệ laser.'
            ],
            [
                'id' => 7,
                'name' => 'Bếp từ Bosch 3 vùng nấu',
                'price' => 15500000,
                'image' => 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?q=80&w=1000&auto=format&fit=crop',
                'slug' => 'bep-tu-bosch-3-vung-nau',
                'description' => 'Bếp từ Bosch chất lượng Đức, nấu nhanh và an toàn.'
            ],
            [
                'id' => 8,
                'name' => 'Máy ép chậm Hurom H310',
                'price' => 7800000,
                'image' => 'https://images.unsplash.com/photo-1622597467827-02302307fc8b?q=80&w=1000&auto=format&fit=crop',
                'slug' => 'may-ep-cham-hurom-h310',
                'description' => 'Máy ép chậm nhỏ gọn, giữ trọn vẹn dưỡng chất trong trái cây.'
            ],
        ];

        return view('store.home', compact('products'));
    }

    public function productDetail($slug)
    {
        // Mocking a product
        $product = [
            'id' => 1,
            'name' => 'Sản phẩm cao cấp',
            'price' => 1500000,
            'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=1000&auto=format&fit=crop',
            'slug' => $slug,
            'description' => 'Đây là mô tả chi tiết cho sản phẩm. Sản phẩm có chất lượng cao, bền bỉ và đẹp mắt.',
            'specs' => [
                'Chất liệu' => 'Da thật',
                'Kích thước' => '20 x 10 cm',
                'Màu sắc' => 'Đen, Nâu'
            ]
        ];

        return view('store.products.show', compact('product'));
    }

    public function cart()
    {
        $cartItems = [
            [
                'id' => 1,
                'name' => 'Sản phẩm 1',
                'price' => 100000,
                'quantity' => 2,
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=1000&auto=format&fit=crop',
            ],
            [
                'id' => 2,
                'name' => 'Sản phẩm 2',
                'price' => 200000,
                'quantity' => 1,
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=1000&auto=format&fit=crop',
            ]
        ];

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cartItems));

        return view('store.cart', compact('cartItems', 'total'));
    }
}
