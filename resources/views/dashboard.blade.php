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
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-4">
                        <div class="mb-2">
                            <svg width="32" height="32" fill="currentColor" class="text-primary opacity-75" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                        <h3 class="fw-bold mb-1" style="font-size: 2rem; color: #6A38C2;">{{ number_format($totalUsers ?? 0) }}</h3>
                        <p class="text-muted mb-0 small">Total Users</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-4">
                        <div class="mb-2">
                            <svg width="32" height="32" fill="currentColor" class="text-success opacity-75" viewBox="0 0 24 24">
                                <path d="M20 4H4v2h16V4zm1 10v-2l-1-5H4l-1 5v2h1v6h10v-6h4v6h2v-6h1zm-9 4H6v-4h6v4z"/>
                            </svg>
                        </div>
                        <h3 class="fw-bold mb-1" style="font-size: 2rem; color: #6A38C2;">{{ number_format($activeVendors ?? 0) }}</h3>
                        <p class="text-muted mb-0 small">Active Vendors</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-4">
                        <div class="mb-2">
                            <svg width="32" height="32" fill="currentColor" class="text-warning opacity-75" viewBox="0 0 24 24">
                                <path d="M20 6h-2.18c.11-.31.18-.65.18-1 0-1.66-1.34-3-3-3-1.05 0-1.96.54-2.5 1.35l-.5.67-.5-.68C10.96 2.54 10.05 2 9 2 7.34 2 6 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-5-2c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM9 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm11 15H4v-2h16v2zm0-5H4V8h5.08L7 10.83 8.62 12 11 8.76l1-1.36 1 1.36L15.38 12 17 10.83 14.92 8H20v6z"/>
                            </svg>
                        </div>
                        <h3 class="fw-bold mb-1" style="font-size: 2rem; color: #6A38C2;">{{ number_format($totalProducts ?? 0) }}</h3>
                        <p class="text-muted mb-0 small">Total Products</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-4">
                        <div class="mb-2">
                            <svg width="32" height="32" fill="currentColor" class="text-danger opacity-75" viewBox="0 0 24 24">
                                <path d="M12 2l-5.5 9h11z"/>
                                <circle cx="12" cy="17" r="1.5"/>
                                <path d="M10 10h4v5h-4z"/>
                            </svg>
                        </div>
                        <h3 class="fw-bold mb-1" style="font-size: 2rem; color: #6A38C2;">{{ number_format($totalCategories ?? 0) }}</h3>
                        <p class="text-muted mb-0 small">Categories</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-4">
                        <div class="mb-2">
                            <svg width="32" height="32" fill="currentColor" class="text-info opacity-75" viewBox="0 0 24 24">
                                <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                            </svg>
                        </div>
                        <h3 class="fw-bold mb-1" style="font-size: 2rem; color: #6A38C2;">Rp{{ number_format($revenueThisMonth ?? 0) }}</h3>
                        <p class="text-muted mb-0 small">Revenue (This month)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Management -->
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Categories</h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.categories') }}" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if(!empty($recentCategories) && $recentCategories->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentCategories->take(6) as $category)
                                    <div class="list-group-item border-0 py-3 d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1 fw-semibold">{{ $category->category_name }}</h6>
                                            <small class="text-muted">{{ $category->category_slug ?? '' }}</small>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Delete this category?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-muted mb-0">No categories yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders, Users & Ads -->
        <div class="row g-3">
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Orders</h5>
                        <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        @if(!empty($recentOrders) && $recentOrders->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentOrders->take(5) as $order)
                                    <div class="list-group-item border-0 py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold">Order #{{ $order->id }}</h6>
                                                <small class="text-muted">{{ $order->user->name ?? 'N/A' }} • Rp{{ number_format($order->order_total_amount ?? 0) }}</small>
                                            </div>
                                            <span class="badge rounded-pill px-3 py-2 {{ $order->order_status === 'paid' ? 'bg-success' : ($order->order_status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}" style="font-size: 0.75rem;">
                                                {{ ucfirst($order->order_status ?? 'pending') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <p class="text-muted mb-0">No orders yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Users</h5>
                        <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        @if(!empty($recentUsers) && $recentUsers->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentUsers->take(5) as $user)
                                    <div class="list-group-item border-0 py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold">{{ $user->name }}</h6>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                            <span class="badge rounded-pill px-3 py-2 {{ $user->role === 'admin' ? 'bg-danger' : ($user->role === 'vendor' ? 'bg-primary' : 'bg-info') }}" style="font-size: 0.75rem;">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <p class="text-muted mb-0">No users yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Ads</h5>
                        <a href="{{ route('admin.ads') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        @if(!empty($recentAds) && $recentAds->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentAds->take(5) as $ad)
                                    <div class="list-group-item border-0 py-3">
                                        <div class="d-flex justify-content-between align-items-center gap-2">
                                            <div class="flex-grow-1 min-w-0">
                                                <h6 class="mb-1 fw-semibold text-truncate">{{ $ad->item->item_name ?? 'N/A' }}</h6>
                                                <small class="text-muted d-block text-truncate">{{ $ad->item->vendor->vendor_name ?? 'Unknown Vendor' }}</small>
                                            </div>
                                            <form action="{{ route('admin.ads.destroy', $ad->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this ad?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" style="padding: 0.25rem 0.5rem;">
                                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <p class="text-muted mb-0">No ads yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    @elseif(auth()->user()->role === 'vendor')
        <!-- Vendor Overview Stats -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-4">
                        <div class="mb-2">
                            <svg width="32" height="32" fill="currentColor" class="text-warning opacity-75" viewBox="0 0 24 24">
                                <path d="M20 6h-2.18c.11-.31.18-.65.18-1 0-1.66-1.34-3-3-3-1.05 0-1.96.54-2.5 1.35l-.5.67-.5-.68C10.96 2.54 10.05 2 9 2 7.34 2 6 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-5-2c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM9 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm11 15H4v-2h16v2zm0-5H4V8h5.08L7 10.83 8.62 12 11 8.76l1-1.36 1 1.36L15.38 12 17 10.83 14.92 8H20v6z"/>
                            </svg>
                        </div>
                        <h3 class="fw-bold mb-1" style="font-size: 2rem; color: #6A38C2;">{{ number_format($vendorProductsCount ?? 0) }}</h3>
                        <p class="text-muted mb-0 small">My Products</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-4">
                        <div class="mb-2">
                            <svg width="32" height="32" fill="currentColor" class="text-success opacity-75" viewBox="0 0 24 24">
                                <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="fw-bold mb-1" style="font-size: 2rem; color: #6A38C2;">{{ number_format($vendorOrdersCount ?? 0) }}</h3>
                        <p class="text-muted mb-0 small">Paid Orders</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-4">
                        <div class="mb-2">
                            <svg width="32" height="32" fill="currentColor" class="text-info opacity-75" viewBox="0 0 24 24">
                                <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                            </svg>
                        </div>
                        <h3 class="fw-bold mb-1" style="font-size: 2rem; color: #6A38C2;">Rp{{ number_format($vendorRevenue ?? 0) }}</h3>
                        <p class="text-muted mb-0 small">Total Revenue</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-4">
                        <div class="mb-2">
                            <svg width="32" height="32" fill="currentColor" class="text-warning opacity-75" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                            </svg>
                        </div>
                        <h3 class="fw-bold mb-1" style="font-size: 2rem; color: #6A38C2;">{{ number_format($vendorRating ?? 0, 1) }}</h3>
                        <p class="text-muted mb-0 small">Store Rating</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products, Orders & Ads Management -->
        <div class="row g-3">
            <!-- Recent Products -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Products</h5>
                        <a href="{{ route('vendor.products.list') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        @if(!empty($vendorRecentProducts) && $vendorRecentProducts->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($vendorRecentProducts->take(5) as $product)
                                    <div class="list-group-item border-0 py-3">
                                        <div class="d-flex gap-3 align-items-center">
                                            <img src="{{ $product->first_image_url }}" alt="{{ $product->item_name }}" class="rounded" style="width:50px;height:50px;object-fit:cover;">
                                            <div class="flex-grow-1 min-w-0">
                                                <h6 class="mb-1 fw-semibold text-truncate">{{ $product->item_name }}</h6>
                                                <small class="text-muted">Rp{{ number_format($product->item_price) }}@if($product->item_type === 'rent') / {{ $product->rental_duration_unit ?? 'day' }}@endif</small>
                                            </div>
                                            <span class="badge rounded-pill px-3 py-2 {{ $product->item_status === 'active' ? 'bg-success' : 'bg-secondary' }}" style="font-size: 0.75rem;">
                                                {{ ucfirst($product->item_status ?? 'active') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <p class="text-muted mb-2">No products yet</p>
                                <a href="{{ route('vendor.products.create') }}" class="btn btn-sm btn-primary">Create Product</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Orders</h5>
                        <a href="{{ route('vendor.orders.list') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        @if(!empty($vendorRecentOrders) && $vendorRecentOrders->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($vendorRecentOrders->take(5) as $order)
                                    <div class="list-group-item border-0 py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold">Order #{{ $order->id }}</h6>
                                                <small class="text-muted">{{ $order->user->name ?? 'N/A' }} • Rp{{ number_format($order->order_total_amount ?? 0) }}</small>
                                            </div>
                                            <span class="badge rounded-pill px-3 py-2 {{ $order->order_status === 'paid' || $order->order_status === 'completed' ? 'bg-success' : ($order->order_status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}" style="font-size: 0.75rem;">
                                                {{ ucfirst($order->order_status ?? 'pending') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <p class="text-muted mb-0">No orders yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Ads Management -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Ads</h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('vendor.ads.create') }}" class="btn btn-sm btn-primary">
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24" class="me-1">
                                    <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                                </svg>
                                New
                            </a>
                            <a href="{{ route('vendor.ads.index') }}" class="btn btn-sm btn-outline-primary">All</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if(!empty($vendorRecentAds) && $vendorRecentAds->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($vendorRecentAds->take(5) as $ad)
                                    <div class="list-group-item border-0 py-3">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div class="flex-grow-1 min-w-0">
                                                <h6 class="mb-1 fw-semibold text-truncate">{{ $ad->item->item_name ?? 'N/A' }}</h6>
                                                <small class="text-muted d-block">
                                                    @if($ad->ad_start_date && $ad->ad_end_date)
                                                        {{ \Carbon\Carbon::parse($ad->ad_start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($ad->ad_end_date)->format('M d, Y') }}
                                                    @else
                                                        No dates set
                                                    @endif
                                                </small>
                                                <span class="badge rounded-pill mt-1 px-2 py-1 
                                                    @if($ad->ad_status === 'active') bg-success
                                                    @elseif($ad->ad_status === 'paused') bg-warning text-dark
                                                    @else bg-secondary
                                                    @endif
                                                " style="font-size: 0.7rem;">
                                                    {{ ucfirst($ad->ad_status ?? 'pending') }}
                                                </span>
                                            </div>
                                            <div class="d-flex flex-column gap-1">
                                                <a href="{{ route('vendor.ads.edit', $ad->id) }}" class="btn btn-sm btn-outline-primary" style="padding: 0.25rem 0.5rem;" title="Edit">
                                                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                                    </svg>
                                                </a>
                                                <form action="{{ route('vendor.ads.destroy', $ad->id) }}" method="POST" onsubmit="return confirm('Delete this ad?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" style="padding: 0.25rem 0.5rem;" title="Delete">
                                                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <svg width="48" height="48" fill="currentColor" class="text-muted opacity-50 mb-3" viewBox="0 0 24 24">
                                    <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V6h16v12zM6 10h2v2H6zm0 4h8v2H6zm10 0h2v2h-2zm0-4h2v2h-2z"/>
                                </svg>
                                <p class="text-muted mb-2">No ads yet</p>
                                <a href="{{ route('vendor.ads.create') }}" class="btn btn-sm btn-primary">Create Ad</a>
                            </div>
                        @endif
                    </div>
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
