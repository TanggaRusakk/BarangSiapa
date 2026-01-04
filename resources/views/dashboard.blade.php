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
                        @if($lastViewed->item_type === 'sewa' || $lastViewed->item_type === 'rent') 
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
        <div class="row g-4 mb-6">
            <div class="col-12 col-md-3">
                <div class="card subtle-hover p-6">
                    <div class="stat-label">Total Users</div>
                    <div class="stat-value text-3xl font-extrabold">{{ number_format($totalUsers) }}</div>
                    <div class="text-sm text-soft-lilac mt-2">
                        <a href="{{ route('admin.users') }}" class="text-decoration-none">Manage users →</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card subtle-hover p-6">
                    <div class="stat-label">Active Vendors</div>
                    <div class="stat-value text-3xl font-extrabold">{{ number_format($activeVendors) }}</div>
                    <div class="text-sm text-soft-lilac mt-2">
                        <a href="{{ route('admin.vendors') }}" class="text-decoration-none">Manage vendors →</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card subtle-hover p-6">
                    <div class="stat-label">Total Products</div>
                    <div class="stat-value text-3xl font-extrabold">{{ number_format($totalProducts) }}</div>
                    <div class="text-sm text-soft-lilac mt-2">
                        <a href="{{ route('admin.items') }}" class="text-decoration-none">Manage items →</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card subtle-hover p-6">
                    <div class="stat-label">Revenue (This Month)</div>
                    <div class="stat-value text-3xl font-extrabold">Rp{{ number_format($revenueThisMonth, 0) }}</div>
                    <div class="text-sm text-soft-lilac mt-2">
                        <a href="{{ route('admin.payments') }}" class="text-decoration-none">View payments →</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-3">
                <a href="{{ route('admin.orders') }}" class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                    <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>
                    </svg>
                    <h3 class="font-bold text-lg mb-1">Orders</h3>
                    <p class="text-sm text-secondary mb-0">Manage all orders</p>
                </a>
            </div>
            <div class="col-12 col-md-3">
                <a href="{{ route('admin.reviews') }}" class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                    <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    <h3 class="font-bold text-lg mb-1">Reviews</h3>
                    <p class="text-sm text-secondary mb-0">View & moderate</p>
                </a>
            </div>
            <div class="col-12 col-md-3">
                <a href="{{ route('admin.messages') }}" class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                    <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    <h3 class="font-bold text-lg mb-1">Messages</h3>
                    <p class="text-sm text-secondary mb-0">View conversations</p>
                </a>
            </div>
            <div class="col-12 col-md-3">
                <a href="{{ route('admin.ads') }}" class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                    <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>
                        <line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>
                    </svg>
                    <h3 class="font-bold text-lg mb-1">Ads</h3>
                    <p class="text-sm text-secondary mb-0">Manage advertisements</p>
                </a>
            </div>
        </div>

        <!-- More Admin Actions -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-3">
                <a href="{{ route('admin.categories') }}" class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                    <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                    </svg>
                    <h3 class="font-bold text-lg mb-1">Categories</h3>
                    <p class="text-sm text-secondary mb-0">Create / edit / delete</p>
                </a>
            </div>
        </div>

        <!-- Admin Quick Actions -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="h6 fw-bold mb-3">Recent Users</h3>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentUsers as $u)
                                        <tr>
                                            <td>{{ $u->name }}</td>
                                            <td>{{ $u->email }}</td>
                                            <td><span class="badge bg-info text-dark">{{ ucfirst($u->role) }}</span></td>
                                            <td><a href="{{ route('admin.users.show', $u) }}" class="btn btn-sm btn-primary">View</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h3 class="h6 fw-bold mb-3">System Alerts</h3>
                        <div class="d-flex flex-column gap-3">
                            <div class="alert alert-danger mb-0 d-flex align-items-start gap-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="flex-shrink-0 mt-1">
                                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                    <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                                </svg>
                                <div>
                                    <div class="fw-bold">Payment Dispute</div>
                                    <p class="mb-0 small">Order #1234 has a payment dispute</p>
                                </div>
                            </div>
                            <div class="alert alert-warning mb-0 d-flex align-items-start gap-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="flex-shrink-0 mt-1">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                                </svg>
                                <div>
                                    <div class="fw-bold">Pending Approvals</div>
                                    <p class="mb-0 small">5 vendors waiting for approval</p>
                                </div>
                            </div>
                            <div class="alert alert-info mb-0 d-flex align-items-start gap-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="flex-shrink-0 mt-1">
                                    <path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/>
                                </svg>
                                <div>
                                    <div class="fw-bold">System Update</div>
                                    <p class="mb-0 small">New features available</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="card">
            <div class="card-body">
                <h3 class="h6 fw-bold mb-3">Recent Orders</h3>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Vendor</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $o)
                                <tr>
                                    <td>#{{ $o->id }}</td>
                                    <td>{{ optional($o->user)->name ?? '—' }}</td>
                                    <td>{{ optional(optional(optional($o->orderItems->first())->item)->vendor)->vendor_name ?? '—' }}</td>
                                    <td>Rp{{ number_format($o->order_total_amount ?? 0, 0) }}</td>
                                    <td><span class="badge bg-info text-dark">{{ ucfirst($o->order_status ?? '—') }}</span></td>
                                    <td><a href="{{ route('admin.orders.show', $o) }}" class="btn btn-sm btn-primary">View</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @elseif(auth()->user()->role === 'vendor')
        <!-- VENDOR DASHBOARD -->
        <div class="row g-4 mb-4">
            <div class="col-6 col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-secondary small">Total Products</div>
                        <div class="h4 fw-bold">{{ number_format($vendorProductsCount) }}</div>
                        <div class="small text-muted">Active items</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-secondary small">Active Orders</div>
                        <div class="h4 fw-bold">0</div>
                        <div class="small text-muted">Pending orders</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-secondary small">Revenue (This Month)</div>
                        <div class="h5 fw-bold">Rp0</div>
                        <div class="small text-muted">Total sales</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-secondary small">Store Rating</div>
                        <div class="h4 fw-bold">0★</div>
                        <div class="small text-muted">No reviews yet</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row g-4 mb-4">
            <div class="col-6 col-md-4">
                <a href="/vendor/products/create" class="text-decoration-none">
                    <div class="card text-center shadow-sm p-3" style="min-height: 120px;">
                        <div class="card-body p-2 d-flex flex-column align-items-center justify-content-center">
                            <svg class="mb-2" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            <h6 class="fw-bold mb-1">Add Product</h6>
                            <p class="small text-secondary mb-0">List a new item</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4">
                <a href="/vendor/products/list" class="text-decoration-none">
                    <div class="card text-center shadow-sm p-3" style="min-height: 120px;">
                        <div class="card-body p-2 d-flex flex-column align-items-center justify-content-center">
                            <svg class="mb-2" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>
                            </svg>
                            <h6 class="fw-bold mb-1">Products</h6>
                            <p class="small text-secondary mb-0">View all items</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a href="/vendor/orders/list" class="text-decoration-none">
                    <div class="card text-center shadow-sm p-3" style="min-height: 120px;">
                        <div class="card-body p-2 d-flex flex-column align-items-center justify-content-center">
                            <svg class="mb-2" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                            </svg>
                            <h6 class="fw-bold mb-1">Orders</h6>
                            <p class="small text-secondary mb-0">Manage orders</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Products & Orders -->
        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="h6 fw-bold mb-0">Your Recent Products</h3>
                            <a href="/vendor/products/list" class="btn btn-sm" style="background: #6A38C2; color: white;">View All →</a>
                        </div>
                        @if($recentProducts->count() > 0)
                            @foreach($recentProducts->take(3) as $prod)
                                <div class="d-flex gap-3 p-3 mb-2 rounded" style="background: rgba(106,56,194,0.03);">
                                    <img src="{{ $prod->first_image_url }}" alt="{{ $prod->item_name }}" class="rounded" style="width:64px;height:64px;object-fit:cover;">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-bold">{{ $prod->item_name }}</h6>
                                        <p class="mb-1 text-secondary small">Rp{{ number_format($prod->item_price) }} @if($prod->item_type === 'sewa' || $prod->item_type === 'rent') • Rent @endif</p>
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                </div>
                            @endforeach
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
                            <h3 class="h6 fw-bold mb-0">Recent Orders</h3>
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
        <!-- Stats Grid -->
        <div class="row g-4 mb-6">
            <div class="col-12 col-md-3">
                <div class="card subtle-hover p-6">
                    <div class="stat-label">Active Orders</div>
                    <div class="stat-value text-3xl font-extrabold">{{ $activeOrdersCount ?? 0 }}</div>
                    <div class="text-sm text-soft-lilac mt-2">{{ $activeOrdersCount > 0 ? 'In progress' : 'No orders yet' }}</div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="card subtle-hover p-6">
                    <div class="stat-label">Active Rentals</div>
                    <div class="stat-value text-3xl font-extrabold">{{ $activeRentalsCount ?? 0 }}</div>
                    <div class="text-sm text-soft-lilac mt-2">{{ $activeRentalsCount > 0 ? 'Currently renting' : 'No rentals' }}</div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="card subtle-hover p-6">
                    <div class="stat-label">Total Spent</div>
                    <div class="stat-value text-3xl font-extrabold">Rp{{ number_format($totalSpent ?? 0) }}</div>
                    <div class="text-sm text-soft-lilac mt-2">Last 30 days</div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="card subtle-hover p-6">
                    <div class="stat-label">Reviews Given</div>
                    <div class="stat-value text-3xl font-extrabold">{{ $reviewsGiven ?? 0 }}</div>
                    <div class="text-sm text-soft-lilac mt-2">Product reviews</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions: 4-column boxes -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-3">
                <a href="{{ route('items.index') }}" class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                    <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                    </svg>
                    <h3 class="font-bold text-lg mb-1">Browse Products</h3>
                    <p class="text-sm text-secondary mb-0">Explore marketplace</p>
                </a>
            </div>

            <div class="col-12 col-md-3">
                <a href="{{ route('orders.my-orders') }}" class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                    <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>
                    </svg>
                    <h3 class="font-bold text-lg mb-1">My Orders</h3>
                    <p class="text-sm text-secondary mb-0">Track your purchases</p>
                </a>
            </div>

            <div class="col-12 col-md-3">
                <a href="{{ url('/messages') }}" class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                    <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    <h3 class="font-bold text-lg mb-1">Messages</h3>
                    <p class="text-sm text-secondary mb-0">Chat with vendors</p>
                </a>
            </div>

            <div class="col-12 col-md-3">
                <a href="{{ route('profile.edit') }}" class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 140px;">
                    <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                    <h3 class="font-bold text-lg mb-1">My Profile</h3>
                    <p class="text-sm text-secondary mb-0">Edit account settings</p>
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

                        @if(isset($userOrders) && $userOrders->count() > 0)
                            <div class="d-flex flex-column gap-3">
                                @foreach($userOrders->take(3) as $order)
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

                        @if(isset($userRentals) && $userRentals->count() > 0)
                            <div class="d-flex flex-column gap-3">
                                @foreach($userRentals->take(3) as $rental)
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
