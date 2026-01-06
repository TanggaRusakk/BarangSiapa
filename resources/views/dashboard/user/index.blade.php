<x-dashboard-layout>
    <x-slot name="title">My Dashboard</x-slot>

    <div class="container-fluid" style="max-width: 1100px;">
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gradient">Welcome back, {{ auth()->user()->name }}</h2>
            <p class="text-sm text-soft-lilac">Overview of your orders and recent activity.</p>
        </div>

        <div class="row g-3 mb-4">
                <div class="col-12">
            <!-- Statistic Cards -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center py-4">
                            <h3 class="fw-bold mb-1" style="font-size: 1.75rem; color: #6A38C2;">{{ number_format($activeOrders ?? 0) }}</h3>
                            <p class="text-muted mb-0 small">Active Orders</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center py-4">
                            <h3 class="fw-bold mb-1" style="font-size: 1.75rem; color: #6A38C2;">Rp{{ number_format($totalSpent ?? 0) }}</h3>
                            <p class="text-muted mb-0 small">Total Spent</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center py-4">
                            <h3 class="fw-bold mb-1" style="font-size: 1.75rem; color: #6A38C2;">{{ number_format($reviewsGiven ?? 0) }}</h3>
                            <p class="text-muted mb-0 small">Reviews Given</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center py-4">
                            <h3 class="fw-bold mb-1" style="font-size: 1.75rem; color: #6A38C2;">{{ number_format($messagesCount ?? 0) }}</h3>
                            <p class="text-muted mb-0 small">Messages</p>
                        </div>
                    </div>
                </div>
            </div>

                </div>
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Recent Orders</h5>
                        <a href="{{ route('orders.my-orders') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body p-3">
                        @if(!empty($recentOrders) && $recentOrders->count() > 0)
                            @foreach($recentOrders as $order)
                                <div class="d-flex justify-content-between align-items-center py-2" style="background: rgba(255,255,255,0.02);">
                                    <div>
                                        <h6 class="mb-1">Order #{{ $order->id }}</h6>
                                        <small class="text-muted">Rp{{ number_format($order->order_total_amount ?? 0) }}</small>
                                    </div>
                                    <span class="badge rounded-pill {{ $order->order_status === 'paid' ? 'bg-success' : 'bg-secondary' }}">{{ $order->order_status }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-secondary py-4">No orders yet</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Recommended Items</h5>
                    </div>
                    <div class="card-body p-3">
                        @if(!empty($recentProducts) && $recentProducts->count() > 0)
                            @foreach($recentProducts as $product)
                                <div class="d-flex align-items-center gap-3 py-2" style="background: rgba(255,255,255,0.02);">
                                    <img src="{{ $product->first_image_url }}" style="width:48px;height:48px;object-fit:cover;" class="rounded">
                                    <div>
                                        <div class="fw-semibold">{{ $product->item_name }}</div>
                                        <small class="text-muted">Rp{{ number_format($product->item_price ?? 0) }}</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-secondary py-4">No recent items</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
