<x-dashboard-layout>
    <x-slot name="title">Dashboard</x-slot>

    {{-- Header --}}
    <div class="mb-5">
        <h1 class="display-6 fw-bold mb-2">Hi, {{ optional(auth()->user())->name ?? 'User' }} 👋</h1>
        <p class="text-muted">
            @if(optional(auth()->user())->role === 'admin')
                Welcome back! Here's your platform overview.
            @elseif(optional(auth()->user())->role === 'vendor')
                Welcome back! Here's your store summary.
            @else
                Welcome back! Here's your recent activity.
            @endif
        </p>
    </div>

    @php $role = optional(auth()->user())->role; @endphp

    @if($role === 'admin')
        {{-- Admin Stats Cards --}}
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                <svg width="24" height="24" fill="currentColor" class="text-primary" viewBox="0 0 24 24">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0 small">Total Users</h6>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-0">{{ number_format($totalUsers ?? 0) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                <svg width="24" height="24" fill="currentColor" class="text-success" viewBox="0 0 24 24">
                                    <path d="M20 4H4v2h16V4zm1 10v-2l-1-5H4l-1 5v2h1v6h10v-6h4v6h2v-6h1zm-9 4H6v-4h6v4z"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0 small">Active Vendors</h6>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-0">{{ number_format($activeVendors ?? 0) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-warning bg-opacity-10 rounded p-2 me-3">
                                <svg width="24" height="24" fill="currentColor" class="text-warning" viewBox="0 0 24 24">
                                    <path d="M20 6h-2.18c.11-.31.18-.65.18-1 0-1.66-1.34-3-3-3-1.05 0-1.96.54-2.5 1.35l-.5.67-.5-.68C10.96 2.54 10.05 2 9 2 7.34 2 6 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-5-2c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM9 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm11 15H4v-2h16v2zm0-5H4V8h5.08L7 10.83 8.62 12 11 8.76l1-1.36 1 1.36L15.38 12 17 10.83 14.92 8H20v6z"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0 small">Total Products</h6>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-0">{{ number_format($totalProducts ?? 0) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-info bg-opacity-10 rounded p-2 me-3">
                                <svg width="24" height="24" fill="currentColor" class="text-info" viewBox="0 0 24 24">
                                    <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0 small">Revenue (Month)</h6>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-0">Rp{{ number_format($revenueThisMonth ?? 0) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Admin Lists --}}
        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4">
                        <h5 class="fw-bold mb-0">Recent Orders</h5>
                    </div>
                    <div class="card-body">
                        @if(!empty($recentOrders) && $recentOrders->count())
                            <div class="list-group list-group-flush">
                                @foreach($recentOrders->take(6) as $o)
                                    <div class="list-group-item border-0 px-0 py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1 fw-semibold">#{{ $o->id }}</h6>
                                                <small class="text-muted">{{ optional($o->user)->name ?? 'N/A' }} • Rp{{ number_format($o->order_total_amount ?? 0) }}</small>
                                            </div>
                                            <span class="badge {{ $o->order_status === 'paid' ? 'bg-success' : 'bg-warning' }}">
                                                {{ ucfirst($o->order_status ?? 'pending') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <p class="mb-0">No recent orders</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4">
                        <h5 class="fw-bold mb-0">Recent Users</h5>
                    </div>
                    <div class="card-body">
                        @if(!empty($recentUsers) && $recentUsers->count())
                            <div class="list-group list-group-flush">
                                @foreach($recentUsers->take(6) as $u)
                                    <div class="list-group-item border-0 px-0 py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1 fw-semibold">{{ $u->name }}</h6>
                                                <small class="text-muted">{{ $u->email }}</small>
                                            </div>
                                            <span class="badge {{ $u->role === 'admin' ? 'bg-danger' : ($u->role === 'vendor' ? 'bg-primary' : 'bg-info') }}">
                                                {{ ucfirst($u->role ?? 'user') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <p class="mb-0">No recent users</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    @elseif($role === 'vendor')
        {{-- Vendor Stats Cards --}}
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                <svg width="24" height="24" fill="currentColor" class="text-primary" viewBox="0 0 24 24">
                                    <path d="M20 6h-2.18c.11-.31.18-.65.18-1 0-1.66-1.34-3-3-3-1.05 0-1.96.54-2.5 1.35l-.5.67-.5-.68C10.96 2.54 10.05 2 9 2 7.34 2 6 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-5-2c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM9 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm11 15H4v-2h16v2zm0-5H4V8h5.08L7 10.83 8.62 12 11 8.76l1-1.36 1 1.36L15.38 12 17 10.83 14.92 8H20v6z"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0 small">My Products</h6>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-0">{{ $vendorProductsCount ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                <svg width="24" height="24" fill="currentColor" class="text-success" viewBox="0 0 24 24">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0 small">Orders</h6>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-0">{{ $ordersCount ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-warning bg-opacity-10 rounded p-2 me-3">
                                <svg width="24" height="24" fill="currentColor" class="text-warning" viewBox="0 0 24 24">
                                    <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0 small">Revenue</h6>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-0">Rp{{ number_format($revenue ?? 0) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-info bg-opacity-10 rounded p-2 me-3">
                                <svg width="24" height="24" fill="currentColor" class="text-info" viewBox="0 0 24 24">
                                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0 small">Store Rating</h6>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-0">{{ $storeRating ?? '0.0' }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Vendor Lists --}}
        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4">
                        <h5 class="fw-bold mb-0">Recent Products</h5>
                    </div>
                    <div class="card-body">
                        @if(!empty($recentProducts) && $recentProducts->count())
                            <div class="list-group list-group-flush">
                                @foreach($recentProducts->take(6) as $p)
                                    <div class="list-group-item border-0 px-0 py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex gap-3 align-items-center">
                                                <img src="{{ $p->first_image_url ?? asset('images/placeholder.png') }}" 
                                                     class="rounded" 
                                                     style="width:50px;height:50px;object-fit:cover">
                                                <div>
                                                    <h6 class="mb-1 fw-semibold">{{ $p->item_name }}</h6>
                                                    <small class="text-muted">Rp{{ number_format($p->item_price ?? 0) }}</small>
                                                </div>
                                            </div>
                                            <span class="badge {{ $p->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $p->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <p class="mb-0">No products yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4">
                        <h5 class="fw-bold mb-0">Recent Orders</h5>
                    </div>
                    <div class="card-body">
                        @if(!empty($recentOrders) && $recentOrders->count())
                            <div class="list-group list-group-flush">
                                @foreach($recentOrders->take(6) as $o)
                                    <div class="list-group-item border-0 px-0 py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1 fw-semibold">#{{ $o->id }}</h6>
                                                <small class="text-muted">Rp{{ number_format($o->order_total_amount ?? 0) }}</small>
                                            </div>
                                            <span class="badge {{ $o->order_status === 'paid' ? 'bg-success' : 'bg-warning' }}">
                                                {{ ucfirst($o->order_status ?? 'pending') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <p class="mb-0">No orders</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    @else
        {{-- Member Logic --}}
        @php
            $userOrders = $userOrders ?? collect();
            $paidOrders = $userOrders->where('order_status', 'paid');

            $paidRentals = $userOrders->filter(function($o){
                $isPaid = ($o->order_status ?? '') === 'paid';
                $isRental = (($o->order_type ?? '') === 'rental') || (optional(optional($o->orderItems->first())->item)->item_type === 'rent');
                return $isPaid && $isRental;
            });

            $totalSpent = $paidOrders->sum('order_total_amount') ?? 0;
        @endphp

        {{-- Member Stats Cards --}}
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                <svg width="24" height="24" fill="currentColor" class="text-primary" viewBox="0 0 24 24">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0 small">Active Orders</h6>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-0">{{ $paidOrders->count() }}</h3>
                        <small class="text-muted">Paid orders</small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                <svg width="24" height="24" fill="currentColor" class="text-success" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0 small">Active Rentals</h6>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-0">{{ $paidRentals->count() }}</h3>
                        <small class="text-muted">Paid rentals</small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-warning bg-opacity-10 rounded p-2 me-3">
                                <svg width="24" height="24" fill="currentColor" class="text-warning" viewBox="0 0 24 24">
                                    <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0 small">Total Spent</h6>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-0">Rp{{ number_format($totalSpent ?? 0) }}</h3>
                        <small class="text-muted">All paid orders</small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-info bg-opacity-10 rounded p-2 me-3">
                                <svg width="24" height="24" fill="currentColor" class="text-info" viewBox="0 0 24 24">
                                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0 small">Reviews Given</h6>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-0">{{ $reviewsGiven ?? 0 }}</h3>
                        <small class="text-muted">Your reviews</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Member Lists --}}
        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4">
                        <h5 class="fw-bold mb-0">Recent Paid Orders</h5>
                    </div>
                    <div class="card-body">
                        @if($paidOrders->count())
                            <div class="list-group list-group-flush">
                                @foreach($paidOrders->take(6) as $o)
                                    <div class="list-group-item border-0 px-0 py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1 fw-semibold">#{{ $o->id }}</h6>
                                                <small class="text-muted">Rp{{ number_format($o->order_total_amount ?? 0) }} • {{ optional($o->created_at)->format('d M Y') }}</small>
                                            </div>
                                            <a href="{{ route('orders.show', $o->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <svg width="64" height="64" fill="currentColor" class="mb-3 opacity-25" viewBox="0 0 24 24">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                </svg>
                                <p class="mb-0">You have no paid orders yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4">
                        <h5 class="fw-bold mb-0">Paid Rentals</h5>
                    </div>
                    <div class="card-body">
                        @if($paidRentals->count())
                            <div class="list-group list-group-flush">
                                @foreach($paidRentals->take(6) as $r)
                                    <div class="list-group-item border-0 px-0 py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1 fw-semibold">#{{ $r->id }}</h6>
                                                <small class="text-muted">Rp{{ number_format($r->order_total_amount ?? 0) }} • {{ optional($r->created_at)->format('d M Y') }}</small>
                                            </div>
                                            <a href="{{ route('orders.show', $r->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <svg width="64" height="64" fill="currentColor" class="mb-3 opacity-25" viewBox="0 0 24 24">
                                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                                <p class="mb-0">You have no paid rentals yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    @endif

</x-dashboard-layout>
