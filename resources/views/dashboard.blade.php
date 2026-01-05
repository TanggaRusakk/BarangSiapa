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
                            <x-dashboard-layout>
                                <x-slot name="title">Dashboard</x-slot>

                                <div style="margin-bottom:16px">
                                    <h1 style="font-size:20px;font-weight:700">Hi, {{ optional(auth()->user())->name ?? 'User' }}</h1>
                                    <p style="color:#6b6b7a;font-size:13px;margin-top:6px">
                                        @if(optional(auth()->user())->role === 'admin')
                                            Platform overview
                                        @elseif(optional(auth()->user())->role === 'vendor')
                                            Store summary
                                        @else
                                            Your recent activity
                                        @endif
                                    </p>
                                </div>

                                @php
                                    $role = optional(auth()->user())->role;
                                @endphp

                                @if($role === 'admin')
                                    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:16px">
                                        <div style="flex:1;min-width:160px;padding:12px;border:1px solid #eee;border-radius:8px">
                                            <div style="font-size:12px;color:#7b7b8a">Total Users</div>
                                            <div style="font-weight:700">{{ number_format($totalUsers ?? 0) }}</div>
                                        </div>
                                        <div style="flex:1;min-width:160px;padding:12px;border:1px solid #eee;border-radius:8px">
                                            <div style="font-size:12px;color:#7b7b8a">Active Vendors</div>
                                            <div style="font-weight:700">{{ number_format($activeVendors ?? 0) }}</div>
                                        </div>
                                        <div style="flex:1;min-width:160px;padding:12px;border:1px solid #eee;border-radius:8px">
                                            <div style="font-size:12px;color:#7b7b8a">Total Products</div>
                                            <div style="font-weight:700">{{ number_format($totalProducts ?? 0) }}</div>
                                        </div>
                                        <div style="flex:1;min-width:160px;padding:12px;border:1px solid #eee;border-radius:8px">
                                            <div style="font-size:12px;color:#7b7b8a">Revenue (This month)</div>
                                            <div style="font-weight:700">Rp{{ number_format($revenueThisMonth ?? 0) }}</div>
                                        </div>
                                    </div>

                                    <div style="display:flex;gap:12px;flex-wrap:wrap">
                                        <div style="flex:1;min-width:320px;padding:12px;border:1px solid #eee;border-radius:8px">
                                            <div style="font-weight:700;margin-bottom:8px">Recent Orders</div>
                                            @if(!empty($recentOrders) && $recentOrders->count())
                                                <ul style="margin:0;padding:0;list-style:none">
                                                    @foreach($recentOrders->take(6) as $o)
                                                        <li style="padding:8px 0;border-top:1px solid #f5f5f5;display:flex;justify-content:space-between">
                                                            <div>
                                                                <div style="font-weight:600">#{{ $o->id }}</div>
                                                                <div style="font-size:12px;color:#7b7b8a">{{ optional($o->user)->name ?? 'N/A' }} • Rp{{ number_format($o->order_total_amount ?? 0) }}</div>
                                                            </div>
                                                            <div style="align-self:center">{{ ucfirst($o->order_status ?? 'pending') }}</div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <div style="color:#7b7b8a">No recent orders</div>
                                            @endif
                                        </div>

                                        <div style="flex:1;min-width:320px;padding:12px;border:1px solid #eee;border-radius:8px">
                                            <div style="font-weight:700;margin-bottom:8px">Recent Users</div>
                                            @if(!empty($recentUsers) && $recentUsers->count())
                                                <ul style="margin:0;padding:0;list-style:none">
                                                    @foreach($recentUsers->take(6) as $u)
                                                        <li style="padding:8px 0;border-top:1px solid #f5f5f5;display:flex;justify-content:space-between">
                                                            <div>
                                                                <div style="font-weight:600">{{ $u->name }}</div>
                                                                <div style="font-size:12px;color:#7b7b8a">{{ $u->email }}</div>
                                                            </div>
                                                            <div style="align-self:center">{{ ucfirst($u->role ?? 'user') }}</div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <div style="color:#7b7b8a">No recent users</div>
                                            @endif
                                        </div>
                                    </div>

                                @elseif($role === 'vendor')
                                    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:16px">
                                        <div style="flex:1;min-width:160px;padding:12px;border:1px solid #eee;border-radius:8px">
                                            <div style="font-size:12px;color:#7b7b8a">My Products</div>
                                            <div style="font-weight:700">{{ $vendorProductsCount ?? 0 }}</div>
                                        </div>
                                        <div style="flex:1;min-width:160px;padding:12px;border:1px solid #eee;border-radius:8px">
                                            <div style="font-size:12px;color:#7b7b8a">Orders</div>
                                            <div style="font-weight:700">{{ $ordersCount ?? 0 }}</div>
                                        </div>
                                        <div style="flex:1;min-width:160px;padding:12px;border:1px solid #eee;border-radius:8px">
                                            <div style="font-size:12px;color:#7b7b8a">Revenue</div>
                                            <div style="font-weight:700">Rp{{ number_format($revenue ?? 0) }}</div>
                                        </div>
                                        <div style="flex:1;min-width:160px;padding:12px;border:1px solid #eee;border-radius:8px">
                                            <div style="font-size:12px;color:#7b7b8a">Rating</div>
                                            <div style="font-weight:700">{{ $storeRating ?? '0.0' }}</div>
                                        </div>
                                    </div>

                                    <div style="display:flex;gap:12px;flex-wrap:wrap">
                                        <div style="flex:1;min-width:320px;padding:12px;border:1px solid #eee;border-radius:8px">
                                            <div style="font-weight:700;margin-bottom:8px">Recent Products</div>
                                            @if(!empty($recentProducts) && $recentProducts->count())
                                                <ul style="margin:0;padding:0;list-style:none">
                                                    @foreach($recentProducts->take(6) as $p)
                                                        <li style="padding:8px 0;border-top:1px solid #f5f5f5;display:flex;justify-content:space-between">
                                                            <div style="display:flex;gap:10px;align-items:center">
                                                                <img src="{{ $p->first_image_url ?? asset('images/placeholder.png') }}" style="width:40px;height:40px;object-fit:cover;border-radius:6px">
                                                                <div>
                                                                    <div style="font-weight:600">{{ $p->item_name }}</div>
                                                                    <div style="font-size:12px;color:#7b7b8a">Rp{{ number_format($p->item_price ?? 0) }}</div>
                                                                </div>
                                                            </div>
                                                            <div style="align-self:center">@if($p->is_active) Active @else Inactive @endif</div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <div style="color:#7b7b8a">No products yet</div>
                                            @endif
                                        </div>

                                        <div style="flex:1;min-width:320px;padding:12px;border:1px solid #eee;border-radius:8px">
                                            <div style="font-weight:700;margin-bottom:8px">Recent Orders</div>
                                            @if(!empty($recentOrders) && $recentOrders->count())
                                                <ul style="margin:0;padding:0;list-style:none">
                                                    @foreach($recentOrders->take(6) as $o)
                                                        <li style="padding:8px 0;border-top:1px solid #f5f5f5;display:flex;justify-content:space-between">
                                                            <div>
                                                                <div style="font-weight:600">#{{ $o->id }}</div>
                                                                <div style="font-size:12px;color:#7b7b8a">Rp{{ number_format($o->order_total_amount ?? 0) }}</div>
                                                            </div>
                                                            <div style="align-self:center">{{ ucfirst($o->order_status ?? 'pending') }}</div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <div style="color:#7b7b8a">No orders</div>
                                            @endif
                                        </div>
                                    </div>

                                @else
                                    {{-- Member view: show paid orders, paid rentals, total spent, reviews given --}}
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

                                    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:16px">
                                        <div style="flex:1;min-width:160px;padding:12px;border:1px solid #eee;border-radius:8px">
                                            <div style="font-size:12px;color:#7b7b8a">Active Orders (Paid)</div>
                                            <div style="font-weight:700">{{ $paidOrders->count() }}</div>
                                        </div>
                                        <div style="flex:1;min-width:160px;padding:12px;border:1px solid #eee;border-radius:8px">
                                            <div style="font-size:12px;color:#7b7b8a">Active Rentals (Paid)</div>
                                            <div style="font-weight:700">{{ $paidRentals->count() }}</div>
                                        </div>
                                        <div style="flex:1;min-width:160px;padding:12px;border:1px solid #eee;border-radius:8px">
                                            <div style="font-size:12px;color:#7b7b8a">Total Spent</div>
                                            <div style="font-weight:700">Rp{{ number_format($totalSpent ?? 0) }}</div>
                                        </div>
                                        <div style="flex:1;min-width:160px;padding:12px;border:1px solid #eee;border-radius:8px">
                                            <div style="font-size:12px;color:#7b7b8a">Reviews Given</div>
                                            <div style="font-weight:700">{{ $reviewsGiven ?? 0 }}</div>
                                        </div>
                                    </div>

                                    <div style="display:flex;gap:12px;flex-wrap:wrap">
                                        <div style="flex:1;min-width:320px;padding:12px;border:1px solid #eee;border-radius:8px">
                                            <div style="font-weight:700;margin-bottom:8px">Recent Paid Orders</div>
                                            @if($paidOrders->count())
                                                <ul style="margin:0;padding:0;list-style:none">
                                                    @foreach($paidOrders->take(6) as $o)
                                                        <li style="padding:8px 0;border-top:1px solid #f5f5f5;display:flex;justify-content:space-between">
                                                            <div>
                                                                <div style="font-weight:600">#{{ $o->id }}</div>
                                                                <div style="font-size:12px;color:#7b7b8a">Rp{{ number_format($o->order_total_amount ?? 0) }} • {{ optional($o->created_at)->format('d M Y') }}</div>
                                                            </div>
                                                            <div style="align-self:center"><a href="{{ route('orders.show', $o->id) }}">View</a></div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <div style="color:#7b7b8a">You have no paid orders yet</div>
                                            @endif
                                        </div>

                                        <div style="flex:1;min-width:320px;padding:12px;border:1px solid #eee;border-radius:8px">
                                            <div style="font-weight:700;margin-bottom:8px">Paid Rentals</div>
                                            @if($paidRentals->count())
                                                <ul style="margin:0;padding:0;list-style:none">
                                                    @foreach($paidRentals->take(6) as $r)
                                                        <li style="padding:8px 0;border-top:1px solid #f5f5f5;display:flex;justify-content:space-between">
                                                            <div>
                                                                <div style="font-weight:600">#{{ $r->id }}</div>
                                                                <div style="font-size:12px;color:#7b7b8a">Rp{{ number_format($r->order_total_amount ?? 0) }} • {{ optional($r->created_at)->format('d M Y') }}</div>
                                                            </div>
                                                            <div style="align-self:center"><a href="{{ route('orders.show', $r->id) }}">View</a></div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <div style="color:#7b7b8a">You have no paid rentals yet</div>
                                            @endif
                                        </div>
                                    </div>

                                @endif

                            </x-dashboard-layout>
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
