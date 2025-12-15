<x-dashboard-layout>
    <x-slot name="title">Vendor Dashboard</x-slot>

    <div class="mb-4">
        <h2 class="text-2xl font-bold text-gradient">Hi, {{ auth()->user()->name }} 👋</h2>
        <p class="text-sm text-soft-lilac">Manage your store — products, orders and earnings.</p>
    </div>

    <!-- Stats Grid -->
    <div class="row g-4 mb-6">
        <div class="col-12 col-md-4">
            <div class="card subtle-hover p-6">
                <div class="stat-label">My Products</div>
                <div class="stat-value text-3xl font-extrabold">{{ $productsCount ?? 0 }}</div>
                <div class="text-sm text-soft-lilac mt-2">Active & draft items</div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card subtle-hover p-6">
                <div class="stat-label">Active Orders</div>
                <div class="stat-value text-3xl font-extrabold">{{ $ordersCount ?? 0 }}</div>
                <div class="text-sm text-soft-lilac mt-2">Recently placed orders</div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card subtle-hover p-6">
                <div class="stat-label">Revenue (This Month)</div>
                <div class="stat-value text-3xl font-extrabold">Rp{{ number_format($revenue ?? 0) }}</div>
                <div class="text-sm text-soft-lilac mt-2">Net sales for current month</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions: clear 3-column boxes -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <a href="{{ route('vendor.products.create') }}" class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                <div class="text-4xl mb-2">➕</div>
                <h3 class="font-bold text-lg mb-1">Add New Product</h3>
                <p class="text-sm text-secondary mb-0">Create product listing</p>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="{{ route('vendor.products.list') }}" class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                <div class="text-4xl mb-2">📦</div>
                <h3 class="font-bold text-lg mb-1">My Products</h3>
                <p class="text-sm text-secondary mb-0">View & manage all items</p>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="{{ route('vendor.orders.list') }}" class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                <div class="text-4xl mb-2">🛒</div>
                <h3 class="font-bold text-lg mb-1">Orders</h3>
                <p class="text-sm text-secondary mb-0">Manage recent orders</p>
            </a>
        </div>
    </div>

    <!-- Recent Products & Orders (2-column layout) -->
    <div class="row g-4">
        <!-- Recent Products -->
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="h5 fw-bold mb-0">Recent Products</h3>
                        <a href="{{ route('vendor.products.list') }}" class="btn btn-sm" style="background: #6A38C2; color: white;">View All →</a>
                    </div>

                    @if(isset($recentProducts) && $recentProducts->count() > 0)
                        <div class="d-flex flex-column gap-3">
                            @foreach($recentProducts->take(3) as $product)
                                <div class="d-flex gap-3 p-3 rounded" style="background: rgba(106,56,194,0.05);">
                                    <img src="{{ $product->first_image_url ?? (file_exists(public_path('images/items/item_placeholder.jpg')) ? asset('images/items/item_placeholder.jpg') : asset('images/items/item_placeholder.png')) }}" alt="{{ $product->item_name }}" class="rounded" style="width:80px;height:80px;object-fit:cover;">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">{{ $product->item_name }}</h6>
                                        <p class="mb-1 text-secondary small">Rp{{ number_format($product->item_price) }}</p>
                                        <span class="badge {{ $product->item_status === 'available' ? 'bg-success' : 'bg-secondary' }} small">{{ ucfirst($product->item_status ?? 'available') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="text-secondary mb-2">📦</div>
                            <p class="text-secondary mb-0">No products yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="h5 fw-bold mb-0">Recent Orders</h3>
                        <a href="{{ route('vendor.orders.list') }}" class="btn btn-sm" style="background: #FF3CAC; color: #000;">View All →</a>
                    </div>

                    @if(isset($recentOrders) && $recentOrders->count() > 0)
                        <div class="d-flex flex-column gap-3">
                            @foreach($recentOrders as $order)
                                <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background: rgba(106,56,194,0.05);">
                                    <div>
                                        <div class="fw-bold">Order #{{ $order->id }}</div>
                                        <div class="small text-secondary">{{ optional($order->user)->name ?? '—' }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold">Rp{{ number_format($order->order_total_amount ?? 0) }}</div>
                                        <div class="small text-secondary">{{ $order->created_at->format('d M Y') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="text-secondary mb-2">🛒</div>
                            <p class="text-secondary mb-0">No orders yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
