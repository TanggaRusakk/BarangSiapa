<x-dashboard-layout>
    <x-slot name="title">Member Dashboard</x-slot>
    
    <!-- Welcome Header -->
    <div class="no-hover">
    <div class="mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="display-5 fw-bold text-gradient mb-1">Welcome back, {{ auth()->user()->name }}! 👋</h1>
                <p class="text-secondary mb-0">Here's what's happening with your account today.</p>
            </div>

            <div class="col-auto">
                <div class="card bg-dark text-white border-0" style="min-width:220px">
                    <div class="card-body p-2 d-flex align-items-center">
                        <img src="{{ auth()->user()->photo_url }}" alt="{{ auth()->user()->name }}" class="rounded-circle me-2" style="width:56px;height:56px;object-fit:cover;border:2px solid rgba(106,56,194,0.6);">
                        <div class="flex-grow-1 text-start">
                            <div class="fw-bold">{{ auth()->user()->name }}</div>
                            <small class="text-secondary">{{ ucfirst(auth()->user()->role ?? 'user') }}</small>
                        </div>
                        @if(auth()->user()->image_path)
                            <form action="{{ route('profile.photo.remove') }}" method="POST" onsubmit="return confirm('Remove profile photo?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($lastViewed))
        <div class="mb-4">
            <h3 class="h5 fw-semibold text-gradient">Last Viewed</h3>
            <div class="card bg-transparent border-0">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <img src="{{ $lastViewed->first_image_url }}" alt="{{ $lastViewed->item_name }}" class="rounded me-3" style="width:80px;height:80px;object-fit:cover;">
                    <div>
                        <h5 class="mb-1 fw-bold">{{ $lastViewed->item_name }}</h5>
                        <p class="mb-0 text-secondary">@if($lastViewed->item_type === 'sewa' || $lastViewed->item_type === 'rent') Rp{{ number_format($lastViewed->item_price) }} / {{ $lastViewed->rental_duration_unit ?? 'day' }} @else Rp{{ number_format($lastViewed->item_price) }} @endif</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(auth()->user()->role === 'admin')
        <!-- ADMIN DASHBOARD -->
        <div class="row g-4 mb-4">
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.users') }}" class="text-decoration-none">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-secondary small">Total Users</div>
                            <div class="h4 fw-bold">{{ number_format($totalUsers) }}</div>
                            <div class="small text-muted">Click to manage →</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.vendors') }}" class="text-decoration-none">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-secondary small">Active Vendors</div>
                            <div class="h4 fw-bold">{{ number_format($activeVendors) }}</div>
                            <div class="small text-muted">Click to manage →</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.items') }}" class="text-decoration-none">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-secondary small">Total Products</div>
                            <div class="h4 fw-bold">{{ number_format($totalProducts) }}</div>
                            <div class="small text-muted">Click to manage →</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.payments') }}" class="text-decoration-none">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-secondary small">Revenue (This Month)</div>
                            <div class="h5 fw-bold">Rp{{ number_format($revenueThisMonth, 0) }}</div>
                            <div class="small text-muted">Click to view →</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- More Admin Actions -->
        <div class="row g-4 mb-4">
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.orders') }}" class="text-decoration-none">
                    <div class="card text-center shadow-sm p-3" style="min-height: 120px;">
                        <div class="card-body p-2">
                            <div class="fs-3 mb-2">📦</div>
                            <h6 class="fw-bold mb-1">Orders</h6>
                            <p class="small text-secondary mb-0">Manage all orders</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.reviews') }}" class="text-decoration-none">
                    <div class="card text-center shadow-sm p-3" style="min-height: 120px;">
                        <div class="card-body p-2">
                            <div class="fs-3 mb-2">⭐</div>
                            <h6 class="fw-bold mb-1">Reviews</h6>
                            <p class="small text-secondary mb-0">View & moderate</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.messages') }}" class="text-decoration-none">
                    <div class="card text-center shadow-sm p-3" style="min-height: 120px;">
                        <div class="card-body p-2">
                            <div class="fs-3 mb-2">💬</div>
                            <h6 class="fw-bold mb-1">Messages</h6>
                            <p class="small text-secondary mb-0">View conversations</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.ads') }}" class="text-decoration-none">
                    <div class="card text-center shadow-sm p-3" style="min-height: 120px;">
                        <div class="card-body p-2">
                            <div class="fs-3 mb-2">📢</div>
                            <h6 class="fw-bold mb-1">Ads</h6>
                            <p class="small text-secondary mb-0">Manage advertisements</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.categories') }}" class="text-decoration-none">
                    <div class="card text-center shadow-sm p-3" style="min-height: 120px;">
                        <div class="card-body p-2">
                            <div class="fs-3 mb-2">🗂</div>
                            <h6 class="fw-bold mb-1">Categories</h6>
                            <p class="small text-secondary mb-0">Create / edit / delete</p>
                        </div>
                    </div>
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
                                <span class="fs-5">⚠️</span>
                                <div>
                                    <div class="fw-bold">Payment Dispute</div>
                                    <p class="mb-0 small">Order #1234 has a payment dispute</p>
                                </div>
                            </div>
                            <div class="alert alert-warning mb-0 d-flex align-items-start gap-2">
                                <span class="fs-5">🔔</span>
                                <div>
                                    <div class="fw-bold">Pending Approvals</div>
                                    <p class="mb-0 small">5 vendors waiting for approval</p>
                                </div>
                            </div>
                            <div class="alert alert-info mb-0 d-flex align-items-start gap-2">
                                <span class="fs-5">📊</span>
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
                        <div class="card-body p-2">
                            <div class="fs-3 mb-2">➕</div>
                            <h6 class="fw-bold mb-1">Add Product</h6>
                            <p class="small text-secondary mb-0">List a new item</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4">
                <a href="/vendor/products/list" class="text-decoration-none">
                    <div class="card text-center shadow-sm p-3" style="min-height: 120px;">
                        <div class="card-body p-2">
                            <div class="fs-3 mb-2">📦</div>
                            <h6 class="fw-bold mb-1">Products</h6>
                            <p class="small text-secondary mb-0">View all items</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a href="/vendor/orders/list" class="text-decoration-none">
                    <div class="card text-center shadow-sm p-3" style="min-height: 120px;">
                        <div class="card-body p-2">
                            <div class="fs-3 mb-2">🛒</div>
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
                                <div class="text-secondary mb-2">📦</div>
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
                            <div class="text-secondary mb-2">🛒</div>
                            <p class="text-secondary mb-0">No orders yet</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @else
        <!-- MEMBER DASHBOARD -->
        <div class="row g-4 mb-4">
            <div class="col-6 col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-secondary small">Active Orders</div>
                        <div class="h4 fw-bold">0</div>
                        <div class="small text-muted">No orders yet</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-secondary small">Active Rentals</div>
                        <div class="h4 fw-bold">0</div>
                        <div class="small text-muted">No rentals</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-secondary small">Total Spent</div>
                        <div class="h5 fw-bold">Rp0</div>
                        <div class="small text-muted">Last 30 days</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-secondary small">Reviews</div>
                        <div class="h4 fw-bold">0</div>
                        <div class="small text-muted">Given by you</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row g-4 mb-4">
            <div class="col-6 col-md-3">
                <a href="{{ route('items.index') }}" class="text-decoration-none">
                    <div class="card text-center shadow-sm p-3" style="min-height: 120px;">
                        <div class="card-body p-2">
                            <div class="fs-3 mb-2">🛍️</div>
                            <h6 class="fw-bold mb-0">Browse</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('orders.my-orders') }}" class="text-decoration-none">
                    <div class="card text-center shadow-sm p-3" style="min-height: 120px;">
                        <div class="card-body p-2">
                            <div class="fs-3 mb-2">📦</div>
                            <h6 class="fw-bold mb-0">Orders</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ url('/messages') }}" class="text-decoration-none">
                    <div class="card text-center shadow-sm p-3" style="min-height: 120px;">
                        <div class="card-body p-2">
                            <div class="fs-3 mb-2">💬</div>
                            <h6 class="fw-bold mb-0">Messages</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('profile.edit') }}" class="text-decoration-none">
                    <div class="card text-center shadow-sm p-3" style="min-height: 120px;">
                        <div class="card-body p-2">
                            <div class="fs-3 mb-2">👤</div>
                            <h6 class="fw-bold mb-0">Profile</h6>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h3 class="h6 fw-bold mb-3">Recent Orders</h3>
                        <div class="text-center py-5">
                            <div class="text-secondary mb-2">📦</div>
                            <p class="text-secondary mb-0">No orders yet</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h3 class="h6 fw-bold mb-3">Active Rentals</h3>
                        <div class="text-center py-5">
                            <div class="text-secondary mb-2">🔑</div>
                            <p class="text-secondary mb-0">No active rentals</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommended Products -->
        <div class="card">
            <div class="card-body">
                <h3 class="h6 fw-bold mb-3">Recommended For You</h3>
                <div class="row g-4">
                    @foreach($recentProducts->take(3) as $prod)
                        @php $isRent = ($prod->item_type === 'sewa' || $prod->item_type === 'rent'); @endphp
                        <div class="col-12 col-md-4">
                            <div class="card shadow-sm h-100">
                                <img src="{{ $prod->first_image_url }}" alt="{{ $prod->item_name }}" class="card-img-top" style="height:180px;object-fit:cover;">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-2">{{ $prod->item_name }}</h6>
                                    <p class="h5 fw-bold text-gradient mb-0">@if($isRent) Rp{{ number_format($prod->item_price) }}/{{ $prod->rental_duration_unit ?? 'day' }} @else Rp{{ number_format($prod->item_price) }} @endif</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    </div>

</x-dashboard-layout>
