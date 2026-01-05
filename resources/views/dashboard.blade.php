<x-dashboard-layout>
    <x-slot name="title">Member Dashboard</x-slot>
    
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
        <!-- ADMIN DASHBOARD -->
        <!-- Stats Grid -->
        <div class="row g-5 mb-6">
            <div class="col-12 col-md-3">
                <div class="card subtle-hover p-6">
                    <div class="stat-label">Total Users</div>
                    <div class="stat-value text-3xl font-extrabold">{{ number_format($totalUsers ?? 0) }}</div>
                    <div class="text-sm text-soft-lilac mt-2">Registered members</div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="card subtle-hover p-6">
                    <div class="stat-label">Active Vendors</div>
                    <div class="stat-value text-3xl font-extrabold">{{ number_format($activeVendors ?? 0) }}</div>
                    <div class="text-sm text-soft-lilac mt-2">Vendor accounts</div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="card subtle-hover p-6">
                    <div class="stat-label">Total Products</div>
                    <div class="stat-value text-3xl font-extrabold">{{ number_format($totalProducts ?? 0) }}</div>
                    <div class="text-sm text-soft-lilac mt-2">Listed items</div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="card subtle-hover p-6">
                    <div class="stat-label">Revenue</div>
                    <div class="stat-value text-3xl font-extrabold">Rp{{ number_format($revenueThisMonth ?? 0) }}</div>
                    <div class="text-sm text-soft-lilac mt-2">This month</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions: 4-column boxes -->
        <div class="row g-5 mb-4 mt-4">
            <div class="col-12 col-md-3">
                <a href="{{ route('admin.users') }}" class="text-decoration-none">
                    <div class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                        <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        <h3 class="font-bold text-lg mb-1">Manage Users</h3>
                        <p class="text-sm text-secondary mb-0">View all users</p>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-3">
                <a href="{{ route('admin.items') }}" class="text-decoration-none">
                    <div class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                        <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>
                        </svg>
                        <h3 class="font-bold text-lg mb-1">Products</h3>
                        <p class="text-sm text-secondary mb-0">Manage all items</p>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-3">
                <a href="{{ route('admin.orders') }}" class="text-decoration-none">
                    <div class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                        <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                        <h3 class="font-bold text-lg mb-1">Orders</h3>
                        <p class="text-sm text-secondary mb-0">View all orders</p>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-3">
                <a href="{{ route('admin.ads') }}" class="text-decoration-none">
                    <div class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                        <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>
                            <line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>
                        </svg>
                        <h3 class="font-bold text-lg mb-1">Advertisements</h3>
                        <p class="text-sm text-secondary mb-0">Manage ads</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Activity (2-column layout) -->
        <div class="row g-5">
            <!-- Recent Orders -->
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="h5 fw-bold mb-0">Recent Orders</h3>
                            <a href="{{ route('admin.orders') }}" class="btn btn-sm" style="background: #6A38C2; color: white;">View All →</a>
                        </div>

                        @if(isset($recentOrders) && $recentOrders->count() > 0)
                            <div class="d-flex flex-column gap-3">
                                @foreach($recentOrders->take(3) as $order)
                                    <div class="d-flex gap-3 p-3 rounded" style="background: rgba(106,56,194,0.05);">
                                        @if($order->orderItems->first() && $order->orderItems->first()->item)
                                            <img src="{{ $order->orderItems->first()->item->first_image_url }}" alt="{{ $order->orderItems->first()->item->item_name }}" class="rounded" style="width:80px;height:80px;object-fit:cover;">
                                        @else
                                            <div class="rounded d-flex align-items-center justify-content-center" style="width:80px;height:80px;background:#f0f0f0;">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold">Order #{{ $order->id }}</h6>
                                            <p class="mb-1 text-secondary small">{{ optional($order->user)->name ?? 'N/A' }} • Rp{{ number_format($order->order_total_amount ?? 0) }}</p>
                                            <span class="badge {{ $order->order_status === 'paid' ? 'bg-success' : ($order->order_status === 'pending' ? 'bg-warning' : 'bg-secondary') }} small">{{ ucfirst($order->order_status ?? 'pending') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <svg class="mb-3 text-secondary" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.3">
                                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                                </svg>
                                <p class="text-secondary mb-0">No orders yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="h5 fw-bold mb-0">Recent Users</h3>
                            <a href="{{ route('admin.users') }}" class="btn btn-sm" style="background: #FF3CAC; color: #000;">View All →</a>
                        </div>

                        @if(isset($recentUsers) && $recentUsers->count() > 0)
                            <div class="d-flex flex-column gap-3">
                                @foreach($recentUsers->take(3) as $user)
                                    <div class="d-flex gap-3 p-3 rounded" style="background: rgba(255,60,172,0.05);">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:80px;height:80px;background:#f0f0f0;">
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                            </svg>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold">{{ $user->name }}</h6>
                                            <p class="mb-1 text-secondary small">{{ $user->email }}</p>
                                            <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : ($user->role === 'vendor' ? 'bg-primary' : 'bg-info') }} small">{{ ucfirst($user->role) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <svg class="mb-3 text-secondary" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.3">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                </svg>
                                <p class="text-secondary mb-0">No users yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    @elseif(auth()->user()->role === 'vendor')
        <!-- VENDOR DASHBOARD -->
        <!-- Stats Grid -->
        <div class="row g-5 mb-6">
            <div class="col-12 col-md-3">
                <div class="card subtle-hover p-6">
                    <div class="stat-label">My Products</div>
                    <div class="stat-value text-3xl font-extrabold">{{ $vendorProductsCount ?? 0 }}</div>
                    <div class="text-sm text-soft-lilac mt-2">Active items</div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="card subtle-hover p-6">
                    <div class="stat-label">Total Orders</div>
                    <div class="stat-value text-3xl font-extrabold">0</div>
                    <div class="text-sm text-soft-lilac mt-2">Pending orders</div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="card subtle-hover p-6">
                    <div class="stat-label">Revenue</div>
                    <div class="stat-value text-3xl font-extrabold">Rp0</div>
                    <div class="text-sm text-soft-lilac mt-2">This month</div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="card subtle-hover p-6">
                    <div class="stat-label">Store Rating</div>
                    <div class="stat-value text-3xl font-extrabold">
                        <span class="text-gradient">0</span>
                        <span class="text-2xl text-soft-lilac">/ 5.0</span>
                    </div>
                    <div class="text-sm text-soft-lilac mt-2">No reviews yet</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions: 4-column boxes -->
        <div class="row g-5 mb-4" style="margin-top:24px;">
            <div class="col-12 col-md-3">
                <a href="/vendor/products/create" class="text-decoration-none">
                    <div class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                        <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        <h3 class="font-bold text-lg mb-1">Add Product</h3>
                        <p class="text-sm text-secondary mb-0">List a new item</p>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-3">
                <a href="/vendor/products/list" class="text-decoration-none">
                    <div class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                        <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>
                        </svg>
                        <h3 class="font-bold text-lg mb-1">Products</h3>
                        <p class="text-sm text-secondary mb-0">View all items</p>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-3">
                <a href="/vendor/orders/list" class="text-decoration-none">
                    <div class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                        <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                        <h3 class="font-bold text-lg mb-1">Orders</h3>
                        <p class="text-sm text-secondary mb-0">Manage orders</p>
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
                        <p class="text-sm text-secondary mb-0">Edit account</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Products & Orders (2-column layout) -->
        <div class="row g-5">
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="h5 fw-bold mb-0">Your Recent Products</h3>
                            <a href="/vendor/products/list" class="btn btn-sm" style="background: #6A38C2; color: white;">View All →</a>
                        </div>

                        @if(isset($recentProducts) && $recentProducts->count() > 0)
                            <div class="d-flex flex-column gap-3">
                                @foreach($recentProducts->take(3) as $prod)
                                    <div class="d-flex gap-3 p-3 rounded" style="background: rgba(106,56,194,0.05);">
                                        <img src="{{ $prod->first_image_url }}" alt="{{ $prod->item_name }}" class="rounded" style="width:80px;height:80px;object-fit:cover;">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold">{{ $prod->item_name }}</h6>
                                            <p class="mb-1 text-secondary small">Rp{{ number_format($prod->item_price) }} @if($prod->item_type === 'rent') • Rent @endif</p>
                                            <span class="badge bg-success small">Active</span>
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
                                <p class="text-secondary mb-0">No products yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="h5 fw-bold mb-0">Recent Orders</h3>
                            <a href="/vendor/orders/list" class="btn btn-sm" style="background: #FF3CAC; color: #000;">View All →</a>
                        </div>

                        <div class="text-center py-5">
                            <svg class="mb-3 text-secondary" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.3">
                                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                            </svg>
                            <p class="text-secondary mb-0">No orders yet</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @else
        <!-- MEMBER DASHBOARD -->

        @php
            $paidOrders = isset($userOrders) ? $userOrders->where('order_status', 'paid') : collect();
            $paidRentals = isset($userOrders) ? $userOrders->filter(function($o){
                $isPaid = ($o->order_status ?? '') === 'paid';
                $isRentalType = (($o->order_type ?? '') === 'rental');
                $firstItemType = optional(optional($o->orderItems->first())->item)->item_type ?? null;
                $hasRentalItem = $firstItemType === 'rent';
                return $isPaid && ($isRentalType || $hasRentalItem);
            }) : collect();
            $totalSpentPaid = $paidOrders->sum('order_total_amount') ?? 0;
        @endphp

        <!-- Stats Grid (lists) -->
        <div class="row g-5 mb-6">
            <div class="col-12 col-lg-6">
                <div class="card subtle-hover p-6 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <div class="stat-label">Active Orders (Paid)</div>
                            <div class="stat-value text-2xl font-extrabold">{{ $paidOrders->count() }}</div>
                        </div>
                        <div class="text-sm text-soft-lilac">Recent paid orders</div>
                    </div>

                    @if($paidOrders->isNotEmpty())
                        <ul class="list-unstyled mb-0">
                            @foreach($paidOrders->take(4) as $order)
                                <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <div>
                                        <strong>Order #{{ $order->id }}</strong>
                                        <div class="text-secondary small">Rp{{ number_format($order->order_total_amount ?? 0) }} • {{ optional($order->created_at)->format('d M Y') }}</div>
                                    </div>
                                    <div>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm">View</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-4 text-secondary">No paid orders yet</div>
                    @endif
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card subtle-hover p-6 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <div class="stat-label">Active Rentals (Paid)</div>
                            <div class="stat-value text-2xl font-extrabold">{{ $paidRentals->count() }}</div>
                        </div>
                        <div class="text-sm text-soft-lilac">Paid rentals</div>
                    </div>

                    @if($paidRentals->isNotEmpty())
                        <ul class="list-unstyled mb-0">
                            @foreach($paidRentals->take(4) as $rental)
                                <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <div>
                                        <strong>Order #{{ $rental->id }}</strong>
                                        <div class="text-secondary small">Rp{{ number_format($rental->order_total_amount ?? 0) }} • {{ optional($rental->created_at)->format('d M Y') }}</div>
                                    </div>
                                    <div>
                                        <a href="{{ route('orders.show', $rental->id) }}" class="btn btn-sm">View</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-4 text-secondary">No paid rentals yet</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row g-5 mb-6">
            <div class="col-12 col-md-6">
                <div class="card subtle-hover p-6">
                    <div class="stat-label">Total Spent</div>
                    <div class="stat-value text-3xl font-extrabold">Rp{{ number_format($totalSpentPaid ?? 0) }}</div>
                    <div class="text-sm text-soft-lilac mt-2">Total from paid orders</div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card subtle-hover p-6">
                    <div class="stat-label">Reviews Given</div>
                    <div class="stat-value text-3xl font-extrabold">{{ $reviewsGiven ?? 0 }}</div>
                    <div class="text-sm text-soft-lilac mt-2">Product reviews</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions: 4-column boxes -->
        <div class="row g-5 mb-4">
            <div class="col-12 col-md-3">
                <a href="{{ route('items.index') }}" class="text-decoration-none">
                    <div class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                        <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                        <h3 class="font-bold text-lg mb-1">Browse Products</h3>
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

    </div>

</x-dashboard-layout>
