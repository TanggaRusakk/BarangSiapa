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
                    <x-dashboard-layout>
                        <x-slot name="title">Member Dashboard</x-slot>

                        <div class="mb-4">
                            <h2 class="text-2xl font-bold">Hi, {{ auth()->user()->name }} 👋</h2>
                            <p class="text-sm text-soft-lilac">
                                @if(auth()->user()->role === 'admin')
                                    Manage the platform — concise overview below.
                                @elseif(auth()->user()->role === 'vendor')
                                    Your store summary and recent activity.
                                @else
                                    Overview of your orders, rentals, and activity.
                                @endif
                            </p>
                        </div>

                        @if(!empty($lastViewed))
                            <div style="border:1px solid #eee;padding:12px;border-radius:8px;margin-bottom:16px;display:flex;gap:12px;align-items:center;">
                                <img src="{{ $lastViewed->first_image_url }}" alt="{{ $lastViewed->item_name }}" style="width:72px;height:72px;object-fit:cover;border-radius:6px;">
                                <div>
                                    <div style="font-weight:700">{{ $lastViewed->item_name }}</div>
                                    <div class="text-sm text-soft-lilac">@if($lastViewed->item_type === 'rent') Rp{{ number_format($lastViewed->item_price) }} / {{ $lastViewed->rental_duration_unit ?? 'day' }} @else Rp{{ number_format($lastViewed->item_price) }} @endif</div>
                                </div>
                            </div>
                        @endif

                        {{-- ADMIN --}}
                        @if(auth()->user()->role === 'admin')
                            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:18px;">
                                <div style="border:1px solid #e6e6e6;padding:16px;border-radius:8px;">
                                    <div class="text-sm text-soft-lilac">Total Users</div>
                                    <div style="font-size:20px;font-weight:700">{{ number_format($totalUsers ?? 0) }}</div>
                                </div>
                                <div style="border:1px solid #e6e6e6;padding:16px;border-radius:8px;">
                                    <div class="text-sm text-soft-lilac">Active Vendors</div>
                                    <div style="font-size:20px;font-weight:700">{{ number_format($activeVendors ?? 0) }}</div>
                                </div>
                                <div style="border:1px solid #e6e6e6;padding:16px;border-radius:8px;">
                                    <div class="text-sm text-soft-lilac">Total Products</div>
                                    <div style="font-size:20px;font-weight:700">{{ number_format($totalProducts ?? 0) }}</div>
                                </div>
                                <div style="border:1px solid #e6e6e6;padding:16px;border-radius:8px;">
                                    <div class="text-sm text-soft-lilac">Revenue (This month)</div>
                                    <div style="font-size:20px;font-weight:700">Rp{{ number_format($revenueThisMonth ?? 0) }}</div>
                                </div>
                            </div>

                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                <div style="border:1px solid #e6e6e6;padding:14px;border-radius:8px;">
                                    <div style="font-weight:700;margin-bottom:8px;">Recent Orders</div>
                                    @if(isset($recentOrders) && $recentOrders->count() > 0)
                                        <ul style="list-style:none;padding:0;margin:0;">
                                            @foreach($recentOrders->take(5) as $order)
                                                <li style="padding:8px 0;border-top:1px solid #f2f2f2;display:flex;justify-content:space-between;align-items:center;">
                                                    <div>
                                                        <div style="font-weight:600">Order #{{ $order->id }}</div>
                                                        <div class="text-sm text-soft-lilac">{{ optional($order->user)->name ?? 'N/A' }} • Rp{{ number_format($order->order_total_amount ?? 0) }}</div>
                                                    </div>
                                                    <div>
                                                        <span class="badge {{ $order->order_status === 'paid' ? 'bg-success' : ($order->order_status === 'pending' ? 'bg-warning' : 'bg-secondary') }}">{{ ucfirst($order->order_status ?? 'pending') }}</span>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-soft-lilac">No recent orders</div>
                                    @endif
                                </div>

                                <div style="border:1px solid #e6e6e6;padding:14px;border-radius:8px;">
                                    <div style="font-weight:700;margin-bottom:8px;">Recent Users</div>
                                    @if(isset($recentUsers) && $recentUsers->count() > 0)
                                        <ul style="list-style:none;padding:0;margin:0;">
                                            @foreach($recentUsers->take(5) as $user)
                                                <li style="padding:8px 0;border-top:1px solid #f2f2f2;display:flex;justify-content:space-between;align-items:center;">
                                                    <div>
                                                        <div style="font-weight:600">{{ $user->name }}</div>
                                                        <div class="text-sm text-soft-lilac">{{ $user->email }}</div>
                                                    </div>
                                                    <div><span class="badge {{ $user->role === 'admin' ? 'bg-danger' : ($user->role === 'vendor' ? 'bg-primary' : 'bg-info') }}">{{ ucfirst($user->role) }}</span></div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-soft-lilac">No recent users</div>
                                    @endif
                                </div>
                            </div>

                        {{-- VENDOR --}}
                        @elseif(auth()->user()->role === 'vendor')
                            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:18px;">
                                <div style="border:1px solid #e6e6e6;padding:16px;border-radius:8px;">
                                    <div class="text-sm text-soft-lilac">My Products</div>
                                    <div style="font-size:20px;font-weight:700">{{ $vendorProductsCount ?? 0 }}</div>
                                </div>
                                <div style="border:1px solid #e6e6e6;padding:16px;border-radius:8px;">
                                    <div class="text-sm text-soft-lilac">Total Orders</div>
                                    <div style="font-size:20px;font-weight:700">0</div>
                                </div>
                                <div style="border:1px solid #e6e6e6;padding:16px;border-radius:8px;">
                                    <div class="text-sm text-soft-lilac">Revenue</div>
                                    <div style="font-size:20px;font-weight:700">Rp0</div>
                                </div>
                                <div style="border:1px solid #e6e6e6;padding:16px;border-radius:8px;">
                                    <div class="text-sm text-soft-lilac">Store Rating</div>
                                    <div style="font-size:20px;font-weight:700">0 / 5</div>
                                </div>
                            </div>

                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                <div style="border:1px solid #e6e6e6;padding:14px;border-radius:8px;">
                                    <div style="font-weight:700;margin-bottom:8px;">Your Recent Products</div>
                                    @if(isset($recentProducts) && $recentProducts->count() > 0)
                                        <ul style="list-style:none;padding:0;margin:0;">
                                            @foreach($recentProducts->take(5) as $prod)
                                                <li style="padding:8px 0;border-top:1px solid #f2f2f2;display:flex;justify-content:space-between;align-items:center;">
                                                    <div style="display:flex;gap:10px;align-items:center;">
                                                        <img src="{{ $prod->first_image_url }}" alt="{{ $prod->item_name }}" style="width:48px;height:48px;object-fit:cover;border-radius:6px;">
                                                        <div>
                                                            <div style="font-weight:600">{{ $prod->item_name }}</div>
                                                            <div class="text-sm text-soft-lilac">Rp{{ number_format($prod->item_price) }} @if($prod->item_type === 'rent') • Rent @endif</div>
                                                        </div>
                                                    </div>
                                                    <div><span class="badge bg-success">Active</span></div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-soft-lilac">No products yet</div>
                                    @endif
                                </div>

                                <div style="border:1px solid #e6e6e6;padding:14px;border-radius:8px;">
                                    <div style="font-weight:700;margin-bottom:8px;">Recent Orders</div>
                                    <div class="text-soft-lilac">No orders yet</div>
                                </div>
                            </div>

                        {{-- MEMBER --}}
                        @else
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

                            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:18px;">
                                <div style="border:1px solid #e6e6e6;padding:16px;border-radius:8px;">
                                    <div class="text-sm text-soft-lilac">Active Orders (Paid)</div>
                                    <div style="font-size:20px;font-weight:700">{{ $paidOrders->count() }}</div>
                                </div>
                                <div style="border:1px solid #e6e6e6;padding:16px;border-radius:8px;">
                                    <div class="text-sm text-soft-lilac">Active Rentals (Paid)</div>
                                    <div style="font-size:20px;font-weight:700">{{ $paidRentals->count() }}</div>
                                </div>
                                <div style="border:1px solid #e6e6e6;padding:16px;border-radius:8px;">
                                    <div class="text-sm text-soft-lilac">Total Spent</div>
                                    <div style="font-size:20px;font-weight:700">Rp{{ number_format($totalSpentPaid ?? 0) }}</div>
                                </div>
                                <div style="border:1px solid #e6e6e6;padding:16px;border-radius:8px;">
                                    <div class="text-sm text-soft-lilac">Reviews Given</div>
                                    <div style="font-size:20px;font-weight:700">{{ $reviewsGiven ?? 0 }}</div>
                                </div>
                            </div>

                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                <div style="border:1px solid #e6e6e6;padding:14px;border-radius:8px;">
                                    <div style="font-weight:700;margin-bottom:8px;">Recent Paid Orders</div>
                                    @if($paidOrders->count() > 0)
                                        <ul style="list-style:none;padding:0;margin:0;">
                                            @foreach($paidOrders->take(6) as $order)
                                                <li style="padding:8px 0;border-top:1px solid #f2f2f2;display:flex;justify-content:space-between;align-items:center;">
                                                    <div>
                                                        <div style="font-weight:600">Order #{{ $order->id }}</div>
                                                        <div class="text-sm text-soft-lilac">Rp{{ number_format($order->order_total_amount ?? 0) }} • {{ optional($order->created_at)->format('d M Y') }}</div>
                                                    </div>
                                                    <div><a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm">View</a></div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-soft-lilac">No paid orders yet</div>
                                    @endif
                                </div>

                                <div style="border:1px solid #e6e6e6;padding:14px;border-radius:8px;">
                                    <div style="font-weight:700;margin-bottom:8px;">Paid Rentals</div>
                                    @if($paidRentals->count() > 0)
                                        <ul style="list-style:none;padding:0;margin:0;">
                                            @foreach($paidRentals->take(6) as $rental)
                                                <li style="padding:8px 0;border-top:1px solid #f2f2f2;display:flex;justify-content:space-between;align-items:center;">
                                                    <div>
                                                        <div style="font-weight:600">Order #{{ $rental->id }}</div>
                                                        <div class="text-sm text-soft-lilac">Rp{{ number_format($rental->order_total_amount ?? 0) }} • {{ optional($rental->created_at)->format('d M Y') }}</div>
                                                    </div>
                                                    <div><a href="{{ route('orders.show', $rental->id) }}" class="btn btn-sm">View</a></div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-soft-lilac">No paid rentals yet</div>
                                    @endif
                                </div>
                            </div>

                        @endif

                    </x-dashboard-layout>
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
