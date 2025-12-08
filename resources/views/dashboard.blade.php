<x-dashboard-layout>
    <x-slot name="title">Member Dashboard</x-slot>
    
    <!-- Welcome Header -->
    <div class="mb-4">
        <h1 class="text-4xl font-bold text-gradient mb-2">Welcome back, {{ Auth::user()->name }}! 👋</h1>
        <p class="text-soft-lilac">Here's what's happening with your account today.</p>
    </div>

    @if(!empty($lastViewed))
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gradient">Last Viewed</h3>
            <div class="flex items-center gap-4 p-3 bg-purple-900 bg-opacity-10 rounded-lg">
                <img src="{{ $lastViewed->first_image_url ?? asset('images/item/default_image.png') }}" alt="{{ $lastViewed->item_name }}" class="w-20 h-20 rounded-lg object-cover">
                <div>
                    <h4 class="font-bold">{{ $lastViewed->item_name }}</h4>
                    <p class="text-sm text-soft-lilac">@if($lastViewed->item_type === 'sewa' || $lastViewed->item_type === 'rent') Rp{{ number_format($lastViewed->item_price) }} / {{ $lastViewed->rental_duration_unit ?? 'day' }} @else Rp{{ number_format($lastViewed->item_price) }} @endif</p>
                </div>
            </div>
        </div>
    @endif

    @if(Auth::user()->role === 'admin')
        <!-- ADMIN DASHBOARD -->
        <div class="stat-grid mb-4">
            <a href="{{ route('admin.users') }}" class="stat-card hover:scale-105 transition cursor-pointer">
                <div class="stat-label">Total Users</div>
                <div class="stat-value">{{ number_format($totalUsers) }}</div>
                <div class="text-sm text-green-400">Click to manage →</div>
            </a>
            <a href="{{ route('admin.vendors') }}" class="stat-card hover:scale-105 transition cursor-pointer">
                <div class="stat-label">Active Vendors</div>
                <div class="stat-value">{{ number_format($activeVendors) }}</div>
                <div class="text-sm text-green-400">Click to manage →</div>
            </a>
            <a href="{{ route('admin.items') }}" class="stat-card hover:scale-105 transition cursor-pointer">
                <div class="stat-label">Total Products</div>
                <div class="stat-value">{{ number_format($totalProducts) }}</div>
                <div class="text-sm text-cyan-400">Click to manage →</div>
            </a>
            <a href="{{ route('admin.payments') }}" class="stat-card hover:scale-105 transition cursor-pointer">
                <div class="stat-label">Revenue (This Month)</div>
                <div class="stat-value">Rp{{ number_format($revenueThisMonth, 0) }}</div>
                <div class="text-sm text-green-400">Click to view →</div>
            </a>
        </div>

        <!-- More Admin Actions -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <a href="{{ route('admin.orders') }}" class="card text-center hover:scale-105 transition cursor-pointer">
                <div class="text-4xl mb-2">📦</div>
                <h3 class="font-bold">Orders</h3>
                <p class="text-sm text-soft-lilac">Manage all orders</p>
            </a>
            <a href="{{ route('admin.reviews') }}" class="card text-center hover:scale-105 transition cursor-pointer">
                <div class="text-4xl mb-2">⭐</div>
                <h3 class="font-bold">Reviews</h3>
                <p class="text-sm text-soft-lilac">View & moderate</p>
            </a>
            <a href="{{ route('admin.messages') }}" class="card text-center hover:scale-105 transition cursor-pointer">
                <div class="text-4xl mb-2">💬</div>
                <h3 class="font-bold">Messages</h3>
                <p class="text-sm text-soft-lilac">View conversations</p>
            </a>
            <a href="{{ route('admin.ads') }}" class="card text-center hover:scale-105 transition cursor-pointer">
                <div class="text-4xl mb-2">📢</div>
                <h3 class="font-bold">Ads</h3>
                <p class="text-sm text-soft-lilac">Manage advertisements</p>
            </a>
        </div>

        <!-- Admin Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div class="card">
                <h3 class="text-xl font-bold mb-3">Recent Users</h3>
                <div class="table-container">
                    <table>
                        <thead>
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
                                        <td><span class="badge badge-info">{{ ucfirst($u->role) }}</span></td>
                                        <td><a href="#" class="btn btn-primary btn-sm">View</a></td>
                                    </tr>
                                @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <h3 class="text-xl font-bold mb-3">System Alerts</h3>
                <div class="space-y-3">
                    <div class="p-3 bg-red-900 bg-opacity-30 rounded-lg border border-red-500">
                        <div class="flex items-center gap-2 mb-1">
                            <span>⚠️</span>
                            <span class="font-bold">Payment Dispute</span>
                        </div>
                        <p class="text-sm text-soft-lilac">Order #1234 has a payment dispute</p>
                    </div>
                    <div class="p-3 bg-yellow-900 bg-opacity-30 rounded-lg border border-yellow-500">
                        <div class="flex items-center gap-2 mb-1">
                            <span>🔔</span>
                            <span class="font-bold">Pending Approvals</span>
                        </div>
                        <p class="text-sm text-soft-lilac">5 vendors waiting for approval</p>
                    </div>
                    <div class="p-3 bg-blue-900 bg-opacity-30 rounded-lg border border-cyan-blue">
                        <div class="flex items-center gap-2 mb-1">
                            <span>📊</span>
                            <span class="font-bold">System Update</span>
                        </div>
                        <p class="text-sm text-soft-lilac">New features available</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="card">
            <h3 class="text-xl font-bold mb-3">Recent Orders</h3>
            <div class="table-container">
                <table>
                    <thead>
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
                                <td>{{ optional(optional($o->orderItems->first())->item->vendor)->vendor_name ?? '—' }}</td>
                                <td>Rp{{ number_format($o->order_total_amount ?? 0, 0) }}</td>
                                <td><span class="badge badge-info">{{ ucfirst($o->order_status ?? '—') }}</span></td>
                                <td><a href="#" class="btn btn-primary btn-sm">View</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @elseif(Auth::user()->role === 'vendor')
        <!-- VENDOR DASHBOARD -->
        <div class="stat-grid mb-4">
            <div class="stat-card">
                <div class="stat-label">Total Products</div>
                <div class="stat-value">{{ number_format($vendorProductsCount) }}</div>
                <div class="text-sm text-cyan-400">3 pending approval</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Active Orders</div>
                <div class="stat-value">28</div>
                <div class="text-sm text-yellow-400">5 need attention</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Revenue (This Month)</div>
                <div class="stat-value">$8,420</div>
                <div class="text-sm text-green-400">↑ 18% from last month</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Store Rating</div>
                <div class="stat-value">4.8★</div>
                <div class="text-sm text-soft-lilac">Based on 234 reviews</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <button class="card text-center hover:scale-105 transition cursor-pointer">
                <div class="text-4xl mb-2">➕</div>
                <h3 class="font-bold">Add New Product</h3>
                <p class="text-sm text-soft-lilac">List a new item</p>
            </button>
            <button class="card text-center hover:scale-105 transition cursor-pointer">
                <div class="text-4xl mb-2">📦</div>
                <h3 class="font-bold">Manage Orders</h3>
                <p class="text-sm text-soft-lilac">View pending orders</p>
            </button>
            <button class="card text-center hover:scale-105 transition cursor-pointer">
                <div class="text-4xl mb-2">📢</div>
                <h3 class="font-bold">Create Ad</h3>
                <p class="text-sm text-soft-lilac">Promote your products</p>
            </button>
        </div>

        <!-- Recent Products & Orders -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
            <div class="card">
                <h3 class="text-xl font-bold mb-3">Your Recent Products</h3>
                <div class="space-y-3">
                    @foreach($recentProducts->take(2) as $prod)
                            <div class="flex gap-3 p-3 bg-purple-900 bg-opacity-20 rounded-lg">
                                <img src="{{ $prod->first_image_url ?? asset('images/item/default_image.png') }}" alt="{{ $prod->item_name }}" class="w-16 h-16 rounded-lg object-cover">
                            <div class="flex-1">
                                <h4 class="font-bold">{{ $prod->item_name }}</h4>
                                <p class="text-sm text-soft-lilac">Rp{{ number_format($prod->item_price) }} @if($prod->item_type === 'sewa' || $prod->item_type === 'rent') • Rent @endif</p>
                                <span class="badge badge-success mt-1">Active</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <h3 class="text-xl font-bold mb-3">Recent Orders</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-purple-900 bg-opacity-20 rounded-lg">
                        <div>
                            <h4 class="font-bold">#ORD-456</h4>
                            <p class="text-sm text-soft-lilac">Premium Headphones</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gradient">$199.00</p>
                            <span class="badge badge-warning">Processing</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-purple-900 bg-opacity-20 rounded-lg">
                        <div>
                            <h4 class="font-bold">#ORD-457</h4>
                            <p class="text-sm text-soft-lilac">Wireless Mouse</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gradient">$49.00</p>
                            <span class="badge badge-success">Completed</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Chart Placeholder -->
        <div class="card">
            <h3 class="text-xl font-bold mb-3">Sales Overview</h3>
            <div class="h-64 bg-purple-900 bg-opacity-20 rounded-lg flex items-center justify-center">
                <p class="text-soft-lilac">📊 Chart visualization will be displayed here</p>
            </div>
        </div>

    @else
        <!-- MEMBER DASHBOARD -->
        <div class="stat-grid mb-4">
            <div class="stat-card">
                <div class="stat-label">Active Orders</div>
                <div class="stat-value">3</div>
                <div class="text-sm text-cyan-400">2 arriving soon</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Active Rentals</div>
                <div class="stat-value">2</div>
                <div class="text-sm text-yellow-400">1 due soon</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Spent</div>
                <div class="stat-value">$1,245</div>
                <div class="text-sm text-soft-lilac">Last 30 days</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Wishlist Items</div>
                <div class="stat-value">12</div>
                <div class="text-sm text-soft-lilac">3 on sale</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <a href="/" class="card text-center hover:scale-105 transition cursor-pointer">
                <div class="text-4xl mb-2">🛍️</div>
                <h3 class="font-bold">Browse Products</h3>
            </a>
            <a href="#" class="card text-center hover:scale-105 transition cursor-pointer">
                <div class="text-4xl mb-2">📦</div>
                <h3 class="font-bold">Track Orders</h3>
            </a>
            <a href="#" class="card text-center hover:scale-105 transition cursor-pointer">
                <div class="text-4xl mb-2">💬</div>
                <h3 class="font-bold">Messages</h3>
            </a>
            <a href="#" class="card text-center hover:scale-105 transition cursor-pointer">
                <div class="text-4xl mb-2">❤️</div>
                <h3 class="font-bold">Wishlist</h3>
            </a>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
            <div class="card">
                <h3 class="text-xl font-bold mb-3">Recent Orders</h3>
                <div class="space-y-3">
                    @foreach($recentProducts->take(2) as $prod)
                        <div class="flex gap-3 p-3 bg-purple-900 bg-opacity-20 rounded-lg">
                            <img src="{{ $prod->first_image_url ?? asset('images/item/default_image.png') }}" alt="{{ $prod->item_name }}" class="w-16 h-16 rounded-lg object-cover">
                            <div class="flex-1">
                                <h4 class="font-bold">{{ $prod->item_name }}</h4>
                                <p class="text-sm text-soft-lilac">Rp{{ number_format($prod->item_price) }}</p>
                                <span class="badge badge-info mt-1">Shipped</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <h3 class="text-xl font-bold mb-3">Active Rentals</h3>
                <div class="space-y-3">
                    <div class="p-3 bg-purple-900 bg-opacity-20 rounded-lg">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-bold">Professional Camera</h4>
                            <span class="badge badge-warning">Active</span>
                        </div>
                        <p class="text-sm text-soft-lilac mb-2">$29/day • Rented 3 days ago</p>
                        <div class="flex justify-between items-center">
                            <span class="text-sm">Due: Jan 15, 2025</span>
                            <button class="btn btn-accent btn-sm">Extend</button>
                        </div>
                    </div>
                    <div class="p-3 bg-purple-900 bg-opacity-20 rounded-lg">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-bold">Gaming Console</h4>
                            <span class="badge badge-warning">Active</span>
                        </div>
                        <p class="text-sm text-soft-lilac mb-2">$15/day • Rented 1 day ago</p>
                        <div class="flex justify-between items-center">
                            <span class="text-sm">Due: Jan 10, 2025</span>
                            <button class="btn btn-accent btn-sm">Extend</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommended Products -->
        <div class="card">
            <h3 class="text-xl font-bold mb-3">Recommended For You</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($recentProducts->take(3) as $prod)
                        @php $isRent = ($prod->item_type === 'sewa' || $prod->item_type === 'rent'); @endphp
                        <div class="product-card">
                            <img src="{{ $prod->first_image_url ?? asset('images/item/default_image.png') }}" alt="{{ $prod->item_name }}" class="product-image">
                            <div class="p-4">
                                <h4 class="font-bold mb-2">{{ $prod->item_name }}</h4>
                                <div class="rating mb-2">
                                    <span class="star">★</span>
                                    <span class="star">★</span>
                                    <span class="star">★</span>
                                    <span class="star">★</span>
                                    <span class="star">★</span>
                                </div>
                                <p class="text-2xl font-bold text-gradient">@if($isRent) Rp{{ number_format($prod->item_price) }} / {{ $prod->rental_duration_unit ?? 'day' }} @else Rp{{ number_format($prod->item_price) }} @endif</p>
                            </div>
                        </div>
                    @endforeach
                </div>
        </div>
    @endif

</x-dashboard-layout>
