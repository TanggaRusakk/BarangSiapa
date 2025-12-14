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
    <div class="row g-4 mb-6">
        <div class="col-12 col-md-4">
            <a href="{{ route('vendor.products.create') }}" class="card subtle-hover text-center p-8 d-flex flex-column align-items-center justify-content-center">
                <div class="text-4xl mb-3">➕</div>
                <h3 class="font-bold text-lg">Add New Product</h3>
                <p class="text-sm text-soft-lilac mt-2">Create product listing</p>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="{{ route('vendor.products.list') }}" class="card subtle-hover text-center p-8 d-flex flex-column align-items-center justify-content-center">
                <div class="text-4xl mb-3">📦</div>
                <h3 class="font-bold text-lg">My Products</h3>
                <p class="text-sm text-soft-lilac mt-2">View & manage all items</p>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="{{ route('vendor.orders.list') }}" class="card subtle-hover text-center p-8 d-flex flex-column align-items-center justify-content-center">
                <div class="text-4xl mb-3">🛒</div>
                <h3 class="font-bold text-lg">Orders</h3>
                <p class="text-sm text-soft-lilac mt-2">Manage recent orders</p>
            </a>
        </div>
    </div>

    <!-- Recent Orders (compact) -->
    <div class="card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-xl font-bold">Recent Orders</h3>
            <a href="{{ route('vendor.orders.list') }}" class="btn btn-secondary btn-sm">View All →</a>
        </div>

        <div class="space-y-3">
            @foreach($recentOrders as $order)
                <div class="p-3 bg-purple-900 bg-opacity-10 rounded d-flex justify-content-between align-items-center subtle-hover">
                    <div>
                        <div class="font-bold">Order #{{ $order->id }}</div>
                        <div class="text-sm text-soft-lilac">Customer: {{ optional($order->user)->name ?? '—' }}</div>
                    </div>
                    <div class="text-end">
                        <div class="font-bold">Rp{{ number_format($order->order_total_amount ?? 0) }}</div>
                        <div class="text-xs text-soft-lilac mt-1">{{ $order->created_at->format('d M Y') }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-dashboard-layout>
