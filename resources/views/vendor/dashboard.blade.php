<x-dashboard-layout>
    <x-slot name="title">Vendor Dashboard</x-slot>

    <div class="container-fluid" style="max-width: 1400px;">
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gradient">Hi, {{ auth()->user()->name }} 👋</h2>
            <p class="text-sm text-soft-lilac">Manage your store — products, orders and earnings.</p>
        </div>

        <!-- Top Stats -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 subtle-hover">
                    <div class="card-body text-center py-4">
                        <div class="mb-2">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor" class="text-muted opacity-75"><path d="M3 3h18v2H3z"/></svg>
                        </div>
                        <h3 class="fw-bold mb-1" style="font-size: 1.75rem; color: #6A38C2;">{{ number_format($productsCount ?? 0) }}</h3>
                        <p class="text-muted mb-0 small">My Products</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 subtle-hover">
                    <div class="card-body text-center py-4">
                        <div class="mb-2">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor" class="text-muted opacity-75"><path d="M3 3h18v2H3z"/></svg>
                        </div>
                        <h3 class="fw-bold mb-1" style="font-size: 1.75rem; color: #6A38C2;">{{ number_format($ordersCount ?? 0) }}</h3>
                        <p class="text-muted mb-0 small">Paid Orders</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 subtle-hover">
                    <div class="card-body text-center py-4">
                        <div class="mb-2">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor" class="text-muted opacity-75"><path d="M3 3h18v2H3z"/></svg>
                        </div>
                        <h3 class="fw-bold mb-1" style="font-size: 1.75rem; color: #6A38C2;">Rp{{ number_format($revenue ?? 0) }}</h3>
                        <p class="text-muted mb-0 small">Total Revenue</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 subtle-hover">
                    <div class="card-body text-center py-4">
                        <div class="mb-2">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor" class="text-muted opacity-75"><path d="M12 2l3 6 6 .5-4.5 3.5L19 20l-7-4-7 4 1.5-7L2 8.5 8 8z"/></svg>
                        </div>
                        <h3 class="fw-bold mb-1" style="font-size: 1.75rem; color: #6A38C2;">{{ $storeRating ?? 0 }} <small class="text-soft-lilac">/5</small></h3>
                        <p class="text-muted mb-0 small">Store Rating</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-md-3">
                <a href="{{ route('vendor.products.create') }}" class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center mb-3" style="min-height: 120px;">
                    <svg class="mb-3" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    <h3 class="font-bold text-lg mb-1">Add Product</h3>
                    <p class="text-sm text-secondary mb-0">Create product listing</p>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <a href="{{ route('vendor.products.list') }}" class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center mb-3" style="min-height: 120px;">
                    <svg class="mb-3" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    </svg>
                    <h3 class="font-bold text-lg mb-1">My Products</h3>
                    <p class="text-sm text-secondary mb-0">View & manage items</p>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <a href="{{ route('vendor.orders.list') }}" class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center mb-3" style="min-height: 120px;">
                    <svg class="mb-3" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                    </svg>
                    <h3 class="font-bold text-lg mb-1">Orders</h3>
                    <p class="text-sm text-secondary mb-0">View & manage orders</p>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <a href="{{ route('vendor.ads.index') }}" class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center mb-3" style="min-height: 120px;">
                    <svg class="mb-3" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>
                    </svg>
                    <h3 class="font-bold text-lg mb-1">My Ads</h3>
                    <p class="text-sm text-secondary mb-0">Create & manage ads</p>
                </a>
            </div>
        </div>

        <!-- Short Lists: Products, Orders, Ads -->
        <div class="row g-3">
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Products</h5>
                        <a href="{{ route('vendor.products.list') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body p-3">
                        @if(isset($recentProducts) && $recentProducts->count() > 0)
                            <div class="d-flex flex-column gap-3">
                                @foreach($recentProducts as $product)
                                    <div class="d-flex align-items-center gap-3 p-2 rounded" style="background: rgba(255,255,255,0.02);">
                                        <img src="{{ $product->first_image_url }}" alt="{{ $product->item_name }}" class="rounded" style="width:56px;height:56px;object-fit:cover;">
                                        <div class="flex-grow-1 min-w-0">
                                            <h6 class="mb-1 fw-bold text-truncate">{{ $product->item_name }}</h6>
                                            <p class="mb-0 text-secondary small text-truncate">Rp{{ number_format($product->item_price ?? 0) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-secondary">No products yet</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Recent Orders</h5>
                        <a href="{{ route('vendor.orders.list') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body p-3">
                        @if(isset($recentOrders) && $recentOrders->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentOrders as $order)
                                    <div class="list-group-item border-0 py-2 d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.02);">
                                        <div class="min-w-0">
                                            <h6 class="mb-1 fw-semibold">Order #{{ $order->id }}</h6>
                                            <small class="text-muted">{{ $order->user->name ?? 'N/A' }} • Rp{{ number_format($order->order_total_amount ?? 0) }}</small>
                                        </div>
                                        <span class="badge rounded-pill px-3 py-2 {{ $order->order_status === 'paid' ? 'bg-success' : ($order->order_status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}" style="font-size: 0.75rem;">
                                            {{ ucfirst($order->order_status ?? 'pending') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-secondary">No recent orders</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Ads</h5>
                        <a href="{{ route('vendor.ads.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body p-3">
                        @if(isset($recentAds) && $recentAds->count() > 0)
                            <div class="d-flex flex-column gap-3">
                                @foreach($recentAds as $ad)
                                    <div class="d-flex gap-3 p-2 rounded" style="background: rgba(255,255,255,0.02);">
                                        @if($ad->ad_image)
                                            <img src="{{ asset('storage/' . $ad->ad_image) }}" alt="Ad Image" class="rounded" style="width:64px;height:64px;object-fit:cover;">
                                        @elseif($ad->item)
                                            <img src="{{ $ad->item->first_image_url }}" alt="{{ $ad->item->item_name }}" class="rounded" style="width:64px;height:64px;object-fit:cover;">
                                        @else
                                            <div class="rounded d-flex align-items-center justify-content-center" style="width:64px;height:64px;background:#f0f0f0;">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/></svg>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold text-truncate">{{ $ad->item->item_name ?? 'Ad #' . $ad->id }}</h6>
                                            <p class="mb-0 text-secondary small">Rp{{ number_format($ad->price ?? 0) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-secondary">No ads yet</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
