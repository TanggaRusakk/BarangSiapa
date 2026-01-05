<x-dashboard-layout>
    <x-slot name="title">Dashboard</x-slot>
    
    <!-- Welcome Header -->
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-gradient">Hi, {{ auth()->user()->name }} 👋</h2>
        <p class="text-sm text-soft-lilac">
            @if(auth()->user()->role === 'admin')
                Manage your platform — users, vendors, and system settings.
            @else
                Track your orders, rentals, and explore the marketplace.
            @endif
        </p>
    </div>

    @if(!empty($lastViewed))
        <div class="card subtle-hover mb-4 p-4">
            <h3 class="stat-label mb-3">Last Viewed</h3>
            <div class="d-flex align-items-center gap-3">
                <img src="{{ $lastViewed->first_image_url }}" alt="{{ $lastViewed->item_name }}" class="rounded" style="width:80px;height:80px;object-fit:cover;">
                <div>
                    <h5 class="mb-1 fw-bold">{{ $lastViewed->item_name }}</h5>
                    <p class="mb-0 text-soft-lilac">
                        @if($lastViewed->item_type === 'rent') 
                            Rp{{ number_format($lastViewed->item_price) }} / {{ $lastViewed->rental_duration_unit ?? 'day' }} 
                        @else 
                            Rp{{ number_format($lastViewed->item_price) }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if(auth()->user()->role === 'admin')
        <!-- Admin Overview Stats -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-3">
                <div class="card stat-card">
                    <div class="stat-value">{{ number_format($totalUsers ?? 0) }}</div>
                    <div class="stat-label">Total Users</div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card stat-card">
                    <div class="stat-value">{{ number_format($activeVendors ?? 0) }}</div>
                    <div class="stat-label">Active Vendors</div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card stat-card">
                    <div class="stat-value">{{ number_format($totalProducts ?? 0) }}</div>
                    <div class="stat-label">Total Products</div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card stat-card">
                    <div class="stat-value">Rp{{ number_format($revenueThisMonth ?? 0) }}</div>
                    <div class="stat-label">Revenue (This month)</div>
                </div>
            </div>
        </div>

        <!-- Recent Orders & Users -->
        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <div class="card subtle-hover">
                    <h3 class="stat-label mb-3">Recent Orders</h3>
                    @if(!empty($recentOrders) && $recentOrders->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentOrders->take(5) as $order)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-1">Order #{{ $order->id }}</h6>
                                            <small class="text-soft-lilac">{{ $order->user->name ?? 'N/A' }} - Rp{{ number_format($order->order_total_amount ?? 0) }}</small>
                                        </div>
                                        <span class="badge {{ $order->order_status === 'paid' ? 'bg-success' : ($order->order_status === 'pending' ? 'bg-warning' : 'bg-secondary') }}">
                                            {{ ucfirst($order->order_status ?? 'pending') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-soft-lilac">No recent orders</p>
                    @endif
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card subtle-hover">
                    <h3 class="stat-label mb-3">Recent Users</h3>
                    @if(!empty($recentUsers) && $recentUsers->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentUsers->take(5) as $user)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-1">{{ $user->name }}</h6>
                                            <small class="text-soft-lilac">{{ $user->email }}</small>
                                        </div>
                                        <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : ($user->role === 'vendor' ? 'bg-primary' : 'bg-info') }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-soft-lilac">No recent users</p>
                    @endif
                </div>
            </div>
        </div>

    @elseif(auth()->user()->role === 'vendor')
        <!-- Vendor Overview Stats -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-3">
                <div class="card stat-card">
                    <div class="stat-value">{{ $vendorProductsCount ?? 0 }}</div>
                    <div class="stat-label">My Products</div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card stat-card">
                    <div class="stat-value">{{ $ordersCount ?? 0 }}</div>
                    <div class="stat-label">Total Orders</div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card stat-card">
                    <div class="stat-value">Rp{{ number_format($revenue ?? 0) }}</div>
                    <div class="stat-label">Revenue</div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card stat-card">
                    <div class="stat-value">{{ $storeRating ?? '0.0' }} / 5</div>
                    <div class="stat-label">Store Rating</div>
                </div>
            </div>
        </div>

        <!-- Recent Products & Orders -->
        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <div class="card subtle-hover">
                    <h3 class="stat-label mb-3">Your Recent Products</h3>
                    @if(!empty($recentProducts) && $recentProducts->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentProducts->take(5) as $product)
                                <div class="list-group-item">
                                    <div class="d-flex gap-3 align-items-center">
                                        <img src="{{ $product->first_image_url }}" alt="{{ $product->item_name }}" class="rounded" style="width:60px;height:60px;object-fit:cover;">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $product->item_name }}</h6>
                                            <small class="text-soft-lilac">Rp{{ number_format($product->item_price) }} @if($product->item_type === 'rent') • Rent @endif</small>
                                        </div>
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-soft-lilac">No products yet</p>
                    @endif
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card subtle-hover">
                    <h3 class="stat-label mb-3">Recent Orders</h3>
                    @if(!empty($recentOrders) && $recentOrders->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentOrders->take(5) as $order)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-1">Order #{{ $order->id }}</h6>
                                            <small class="text-soft-lilac">Rp{{ number_format($order->order_total_amount ?? 0) }}</small>
                                        </div>
                                        <span class="badge {{ $order->order_status === 'paid' ? 'bg-success' : ($order->order_status === 'pending' ? 'bg-warning' : 'bg-secondary') }}">
                                            {{ ucfirst($order->order_status ?? 'pending') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-soft-lilac">No orders yet</p>
                    @endif
                </div>
            </div>
        </div>

    @else
        @php
            // Member user - calculate paid orders and rentals
            $paidOrders = $userOrders->filter(function($order) {
                return $order->order_status === 'paid';
            });

            $paidRentals = $userOrders->filter(function($order) {
                if ($order->order_status !== 'paid') return false;
                
                // Check if it's a rental order
                if ($order->order_type === 'rental') return true;
                
                // Or check if the first item is a rental
                $firstItem = $order->orderItems->first();
                if ($firstItem && $firstItem->item && $firstItem->item->item_type === 'rent') {
                    return true;
                }
                
                return false;
            });

            $totalSpent = $paidOrders->sum('order_total_amount');
        @endphp

        <!-- User Stats -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-3">
                <div class="card stat-card">
                    <div class="stat-value">{{ $paidOrders->count() }}</div>
                    <div class="stat-label">Active Orders (Paid)</div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card stat-card">
                    <div class="stat-value">{{ $paidRentals->count() }}</div>
                    <div class="stat-label">Active Rentals (Paid)</div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card stat-card">
                    <div class="stat-value">Rp{{ number_format($totalSpent) }}</div>
                    <div class="stat-label">Total Spent</div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card stat-card">
                    <div class="stat-value">{{ $reviewsGiven ?? 0 }}</div>
                    <div class="stat-label">Reviews Given</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-3">
                <a href="{{ url('/') }}" class="text-decoration-none">
                    <div class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                        <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                        </svg>
                        <h3 class="font-bold text-lg mb-1">Browse Items</h3>
                        <p class="text-sm text-secondary mb-0">Explore marketplace</p>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-3">
                <a href="{{ route('orders.my-orders') }}" class="text-decoration-none">
                    <div class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                        <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>
                        </svg>
                        <h3 class="font-bold text-lg mb-1">My Orders</h3>
                        <p class="text-sm text-secondary mb-0">Track your purchases</p>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-3">
                <a href="{{ url('/messages') }}" class="text-decoration-none">
                    <div class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                        <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                        <h3 class="font-bold text-lg mb-1">Messages</h3>
                        <p class="text-sm text-secondary mb-0">Chat with vendors</p>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-3">
                <a href="{{ route('profile.edit') }}" class="text-decoration-none">
                    <div class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                        <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        <h3 class="font-bold text-lg mb-1">My Profile</h3>
                        <p class="text-sm text-secondary mb-0">Edit account settings</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Orders & Rentals (2-column layout) -->
        <div class="row g-4">
            <!-- Recent Orders -->
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="h5 fw-bold mb-0">Recent Orders</h3>
                            <a href="{{ route('orders.my-orders') }}" class="btn btn-sm" style="background: #6A38C2; color: white;">View All →</a>
                        </div>

                        @if($paidOrders->count() > 0)
                            <div class="d-flex flex-column gap-3">
                                @foreach($paidOrders->take(3) as $order)
                                    <div class="d-flex gap-3 p-3 rounded" style="background: rgba(106,56,194,0.05);">
                                        @if($order->orderItems->first() && $order->orderItems->first()->item)
                                            <img src="{{ $order->orderItems->first()->item->first_image_url }}" alt="{{ $order->orderItems->first()->item->item_name }}" class="rounded" style="width:80px;height:80px;object-fit:cover;">
                                        @else
                                            <div class="rounded d-flex align-items-center justify-content-center" style="width:80px;height:80px;background:#f0f0f0;">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold">Order #{{ $order->id }}</h6>
                                            <p class="mb-1 text-secondary small">Rp{{ number_format($order->order_total_amount ?? 0) }} • {{ $order->created_at->format('d M Y') }}</p>
                                            <span class="badge {{ $order->order_status === 'paid' ? 'bg-success' : ($order->order_status === 'pending' ? 'bg-warning' : 'bg-secondary') }} small">{{ ucfirst($order->order_status ?? 'pending') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <svg class="mb-3 text-secondary" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.3">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>
                                </svg>
                                <p class="text-secondary mb-0">No orders yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Active Rentals -->
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="h5 fw-bold mb-0">Active Rentals</h3>
                            <a href="{{ route('orders.my-orders') }}" class="btn btn-sm" style="background: #FF3CAC; color: #000;">View All →</a>
                        </div>

                        @if($paidRentals->count() > 0)
                            <div class="d-flex flex-column gap-3">
                                @foreach($paidRentals->take(3) as $rental)
                                    <div class="d-flex gap-3 p-3 rounded" style="background: rgba(255,60,172,0.05);">
                                        @if($rental->orderItems->first() && $rental->orderItems->first()->item)
                                            <img src="{{ $rental->orderItems->first()->item->first_image_url }}" alt="{{ $rental->orderItems->first()->item->item_name }}" class="rounded" style="width:80px;height:80px;object-fit:cover;">
                                        @else
                                            <div class="rounded d-flex align-items-center justify-content-center" style="width:80px;height:80px;background:#f0f0f0;">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold">{{ $rental->orderItems->first()->item->item_name ?? 'Rental Item' }}</h6>
                                            <p class="mb-1 text-secondary small">Rp{{ number_format($rental->order_total_amount ?? 0) }} • {{ $rental->created_at->format('d M Y') }}</p>
                                            <span class="badge {{ $rental->order_status === 'paid' ? 'bg-success' : ($rental->order_status === 'pending' ? 'bg-warning' : 'bg-secondary') }} small">{{ ucfirst($rental->order_status ?? 'pending') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <svg class="mb-3 text-secondary" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.3">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                                <p class="text-secondary mb-0">No active rentals</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    @endif

</x-dashboard-layout>
