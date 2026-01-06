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
                        <h3 class="fw-bold mb-1" style="font-size: 1.75rem; color: #6A38C2;">{{ number_format($productsCount ?? 0) }}</h3>
                        <p class="text-muted mb-0 small">My Products</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 subtle-hover">
                    <div class="card-body text-center py-4">
                        <h3 class="fw-bold mb-1" style="font-size: 1.75rem; color: #6A38C2;">{{ number_format($ordersCount ?? 0) }}</h3>
                        <p class="text-muted mb-0 small">Paid Orders</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 subtle-hover">
                    <div class="card-body text-center py-4">
                        <h3 class="fw-bold mb-1" style="font-size: 1.75rem; color: #6A38C2;">Rp{{ number_format($revenue ?? 0) }}</h3>
                        <p class="text-muted mb-0 small">Total Revenue</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 subtle-hover">
                    <div class="card-body text-center py-4">
                        <h3 class="fw-bold mb-1" style="font-size: 1.75rem; color: #6A38C2;">{{ $storeRating ?? 0 }} <small class="text-soft-lilac">/5</small></h3>
                        <p class="text-muted mb-0 small">Store Rating</p>
                    </div>
                </div>
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
