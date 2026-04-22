@extends('admin.layout')

@section('title', 'Tổng quan')

@php
    $statCards = [
        [
            'label' => 'Tổng doanh thu',
            'value' => number_format($totalRevenue) . 'đ',
            'hint' => 'Toàn bộ doanh thu đã ghi nhận',
            'icon' => 'fa-wallet',
            'tone' => 'revenue',
        ],
        [
            'label' => 'Đơn hàng mới',
            'value' => number_format($newOrdersCount),
            'hint' => 'Phát sinh trong 7 ngày gần nhất',
            'icon' => 'fa-bag-shopping',
            'tone' => 'orders',
        ],
        [
            'label' => 'Khách hàng mới',
            'value' => number_format($newCustomersCount),
            'hint' => 'Tạo mới trong 30 ngày gần nhất',
            'icon' => 'fa-user-plus',
            'tone' => 'customers',
        ],
        [
            'label' => 'Sắp hết hàng',
            'value' => number_format($lowStockProductsCount),
            'hint' => 'Sản phẩm còn tồn kho dưới ngưỡng',
            'icon' => 'fa-triangle-exclamation',
            'tone' => 'stock',
        ],
    ];
@endphp

@section('content')
    <section class="dashboard-page">
        <div class="dashboard-overview">
            <div>
                <p class="dashboard-overview__eyebrow">Vận hành cửa hàng</p>
                <h2 class="dashboard-overview__title">Theo dõi nhanh tình hình kinh doanh mỗi ngày</h2>
            </div>

            <a href="{{ route('admin.orders.index') }}" class="dashboard-overview__action">
                Xem tất cả đơn hàng
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="dashboard-stats">
            @foreach ($statCards as $card)
                <article class="dashboard-stat-card tone-{{ $card['tone'] }}">
                    <div class="dashboard-stat-card__icon">
                        <i class="fa-solid {{ $card['icon'] }}"></i>
                    </div>
                    <div class="dashboard-stat-card__content">
                        <p>{{ $card['label'] }}</p>
                        <strong>{{ $card['value'] }}</strong>
                        <span>{{ $card['hint'] }}</span>
                    </div>
                </article>
            @endforeach
        </div>

        <section class="dashboard-panel">
            <div class="dashboard-panel__header">
                <div>
                    <h3>Đơn hàng mới nhất</h3>
                    <p>Danh sách đơn đặt hàng từ website, hiển thị rõ khách hàng, sản phẩm và trạng thái xử lý.</p>
                </div>
                <span class="dashboard-panel__count">{{ $latestOrders->count() }} đơn gần đây</span>
            </div>

            <div class="dashboard-table-wrap">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Sản phẩm</th>
                            <th>Tổng tiền</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestOrders as $order)
                            <tr>
                                <td>
                                    <strong>#{{ $order->order_code }}</strong>
                                    <span>{{ $order->payment_method === 'transfer' ? 'Chuyển khoản' : 'Thanh toán khi nhận hàng' }}</span>
                                </td>
                                <td>
                                    <strong>{{ $order->customer?->name ?? $order->shipping_name }}</strong>
                                    <span>{{ $order->shipping_phone }}</span>
                                </td>
                                <td>
                                    @php
                                        $itemSummary = $order->items->take(2)->map(function ($item) {
                                            return $item->product?->name;
                                        })->filter()->implode(', ');
                                    @endphp
                                    <strong>{{ $order->items->count() }} sản phẩm</strong>
                                    <span>{{ $itemSummary ?: 'Chưa có chi tiết sản phẩm' }}</span>
                                </td>
                                <td>
                                    <strong>{{ number_format($order->grand_total) }}đ</strong>
                                    <span>{{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}</span>
                                </td>
                                <td>
                                    <strong>{{ optional($order->placed_at ?? $order->created_at)->format('d/m/Y') }}</strong>
                                    <span>{{ optional($order->placed_at ?? $order->created_at)->format('H:i') }}</span>
                                </td>
                                <td>
                                    <x-admin.status-badge :status="$order->status" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="dashboard-table__empty">Chưa có đơn hàng mới để hiển thị.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </section>

@endsection
