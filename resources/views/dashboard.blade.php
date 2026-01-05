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

    @php $role = optional(auth()->user())->role; @endphp

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
